<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:26
 */
declare(strict_types=1);


namespace app\common\services\system;

use app\common\helper\StringHelper;
use app\common\services\common\JwtAuthService;
use app\common\services\common\CacheService;
use app\common\exception\CommonException;
use app\common\helper\ArrayHelper;
use app\common\dao\system\AdminDao;

use think\facade\Db;
/**
 * 账号service
 * Class AdminService
 */
class AdminService extends \app\common\services\BaseService
{

    /**
     * AdminServices constructor.
     * @param AdminDao $dao
     */
    public function __construct(AdminDao $dao)
    {
        $this->dao = $dao;
    }

    public function getDetail($filter,$exception = true)
    {
        $detail = $this->dao->detail($filter);
        if (!$detail ) {
            if(!$exception){
                return [];
            }
            throw new CommonException('数据不存在');
        }
        return $detail->toArray();
    }

    /**
     * 获取列表
     */
    public function getAdminList(array $param = []): array
    {
        [$page, $limit] = $this->dao->getPageValue();
        $filter[] = ['level', '=', 1];
        if(!empty($param['account_like'])){
            $filter[] = ['account','like',"%{$param['account_like']}%"];
        }
        if(!empty($param['time']) && is_array($param['time'])){
            $filter[] = ['create_time', 'between', [StringHelper::_strtotime($param['time'][0]), StringHelper::_strtotime($param['time'][1])]];
        }
        $list = $this->dao->getAdminList($filter, $page, $limit);
        return $list;
    }

    // 账号登陆
    public function verifyLogin(string $account, string $password)
    {
        $adminInfo = $this->dao->accountByAdmin($account);
        if (!$adminInfo) {
            throw new CommonException('账号不存在!');
        }
        if (!$adminInfo->status) {
            throw new CommonException('您已被禁止登录!');
        }
        if (!password_verify($password, $adminInfo->pwd)) {
            throw new CommonException('账号或密码错误，请重新输入',701);
        }
        $adminInfo->last_time = time();
        $adminInfo->last_ip = app('request')->ip();
        $adminInfo->login_count++;
        $adminInfo->save();

        return $adminInfo;
    }

    // 后台登陆获取菜单获取token
    public function login(string $account, string $password, string $type): array
    {
        $adminInfo = $this->verifyLogin($account, $password);

        $tokenInfo = JwtAuthService::instance()->createToken($adminInfo->id, $type);
        $menusService = MenusService::instance();
        $menus = $menusService->getMenusList($adminInfo->roles, (int)$adminInfo['level']);
        $exp =  $tokenInfo['params']['exp'];
        if((int)$adminInfo['level'] < 1){
            $rolesApi = -1;
        }else{
            $rolesApi = $menusService->getApiList($adminInfo->roles, (int)$adminInfo['level']);
        }
        CacheService::instance()->set('system:admin:roles:'.$adminInfo->id,$rolesApi,$exp);
        return [
            'token' => $tokenInfo['token'],
            'expires_time' => $exp,
            'menus' => $menus,
            'user_info' => $adminInfo->hidden(['pwd'])
        ];
    }


    /**
     * 创建账号
     */
    public function create(array $data): bool
    {
        unset($data['conf_pwd']);
        if ($this->dao->count(['account' => $data['account'], 'delete_time' => 0])) {
            throw new CommonException('账号已存在');
        }

        $data['pwd'] = $this->dao->passwordHash($data['pwd']);
        $data['add_time'] = time();

        Db::startTrans();
        try {

            $this->dao->save($data);
            // 事务提交
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::collback();
            return false;
        }
    }

    /**
     * 修改账号
     */
    public function updateSave($id, array $data): bool
    {
        if (!$adminInfo = $this->getDetail($id)) {
            throw new CommonException('账号不存在,无法修改');
        }
        if ($adminInfo['delete_time']) {
            throw new CommonException('账号已经删除');
        }
        //修改密码
        if ($data['pwd']) {
            $data['pwd'] = $this->dao->passwordHash($data['pwd']);
        }else{
            unset($data['pwd']);
        }
        //修改账号
        if (isset($data['account']) && $data['account'] != $adminInfo['account'] && $this->dao->isAccountUsable($data['account'], $id)) {
            throw new CommonException('账号已存在');
        }

        Db::startTrans();
        try {

            $this->dao->update($id,$data);
            // 事务提交
            Db::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::collback();
            return false;
        }
    }

    /**
     * 修改密码
     */
    public function updatePass(array $data): bool
    {
        $adminId = request()->adminId();
        $detail = $this->getDetail(request()->adminId());
        if (!$detail) {
            throw new CommonException('账号不存在!');
        }
        if (!password_verify($data['old_pwd'], $detail['pwd'])) {
            throw new CommonException('原密码错误，请重新输入');
        }

        //修改密码
        $pwd = $this->dao->passwordHash($data['pwd']);
        if ($this->dao->update($adminId,['pwd'=>$pwd])) {
            return true;
        } else {
            return false;
        }
    }
}

