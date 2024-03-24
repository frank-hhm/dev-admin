<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2024/3/22 18:44
 */
declare(strict_types=1);
namespace app\swoole\websocket\sys;

use app\common\exception\CommonException;
use app\common\helper\StringHelper;
use app\common\services\common\CacheService;
use app\common\services\system\AdminAuthService;
use app\swoole\websocket\Base;
use app\swoole\websocket\Response;

class Index extends Base
{
    public function login($data = []){
        if (!isset($data['token']) || !$token = $data['token']) {
            return $this->response->error('授权失败!');
        }

        try {
            $adminAuthService = app(AdminAuthService::class);
            $authInfo         = $adminAuthService->parseToken($token);
        } catch (\Exception $e) {
            return $this->response->error($e->getMessage());
        }

        if (!$authInfo || !isset($authInfo['info']['id'])) {
            return $this->response->error('授权失败!');
        }

        return $this->response->success(['uid' => $authInfo['info']['id']]);
    }


}