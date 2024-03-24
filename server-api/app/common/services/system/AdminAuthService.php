<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:26
 */
declare(strict_types=1);

namespace app\common\services\system;

use app\common\dao\system\AdminDao;
use app\common\services\BaseService;
use app\common\services\common\CacheService;
use app\common\services\common\JwtAuthService;
use app\common\constants\StatusCode;
use app\common\exception\CommonException;
use Firebase\JWT\ExpiredException;

use Psr\SimpleCache\InvalidArgumentException;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Env;

/**
 * admin授权service
 * Class AdminAuthService
 * @package app\common\services\system
 */
class AdminAuthService extends BaseService
{

    /**
     * 构造方法
     * AdminAuthService constructor.
     * @param App $app
     * @param AdminDao $dao
     */
    public function __construct(App $app,AdminDao $dao)
    {
        parent::__construct($app);
        $this->dao = $dao;
    }

    /**
     * 获取Admin授权信息
     * @param string $token
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function parseToken(string $token): array
    {
        $cacheService = CacheService::instance();
        if (!$token || $token === 'undefined') {
            throw new CommonException(StatusCode::ERR_LOGIN);
        }
        //设置解析token
        [$id, $type] = JwtAuthService::instance()->parseToken($token);
        //检测token是否过期
        $md5Token = Env::get('redis.jwt').md5($token);
        if (!$cacheService->has($md5Token) || !($cacheToken = $cacheService->get($md5Token))) {
            $this->authFailAfter($id, $type);
            throw new CommonException(StatusCode::ERR_LOGIN);
        }
        //是否超出有效次数
        if (isset($cacheToken['invalidNum']) && $cacheToken['invalidNum'] >= 3) {
            if (!request()->isCli()) {
                $cacheService->clear($md5Token);
            }
            $this->authFailAfter($id, $type);
            throw new CommonException(StatusCode::ERR_LOGIN);
        }


        //验证token
        try {
            JwtAuthService::instance()->verifyToken();
            $cacheService->set($md5Token, $cacheToken, $cacheToken['exp']);
        } catch (ExpiredException $e) {
            $cacheToken['invalidNum'] = isset($cacheToken['invalidNum']) ? $cacheToken['invalidNum']++ : 1;
            $cacheService->set($md5Token, $cacheToken, $cacheToken['exp']);
        } catch (\Exception $e) {
            if (!request()->isCli()) {
                $cacheService->clear($md5Token);
            }
            $this->authFailAfter($id, $type);
            throw new CommonException(StatusCode::ERR_LOGIN);
        }

        //获取管理员信息
        $adminInfo = $this->dao->model->where('id',$id)->find();

        if (!$adminInfo || !$adminInfo->id) {
            if (!request()->isCli()) {
                $cacheService->clear($md5Token);
            }
            $this->authFailAfter($id, $type);
            throw new CommonException(StatusCode::ERR_LOGIN);
        }

        $adminInfo->type = $type;
        $roles = CacheService::instance()->get('system:admin:roles:'.$adminInfo->id);
        $res['roles'] = $roles;
        $res['info'] = $adminInfo->toArray();
        return $res;

    }

    /**
     * token验证失败后事件
     */
    protected function authFailAfter($id, $type): void
    {
    }
}