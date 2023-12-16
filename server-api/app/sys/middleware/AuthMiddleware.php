<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 15:55
 */
declare(strict_types=1);

namespace app\sys\middleware;

use app\common\constants\StatusCode;
use app\common\exception\CommonException;
use app\common\middleware\MiddlewareInterface;
use app\Request;
use think\facade\Config;
use app\common\services\system\AdminAuthService;

/**
 * 后台登陆验证中间件
 * Class AuthMiddleware
 * @package app\sys\middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next)
    {
        $authInfo = null;
        $AuthoriZation = $request->header(Config::get('cookie.token_name', 'Authori-zation'));

        if (empty($AuthoriZation)) {
            throw new CommonException(StatusCode::ERR_LOGIN);
        }

        $token = trim(ltrim($request->header(Config::get('cookie.token_name', 'Authori-zation')), 'Bearer'));
        $authData = AdminAuthService::instance()->parseToken($token);
        $adminInfo = $authData['info'] ?? [];
        $roles = $authData['roles'] ?? [];
        Request::macro('isAdminLogin', function () use (&$adminInfo) {
            return !is_null($adminInfo);
        });
        Request::macro('adminId', function () use (&$adminInfo) {
            return $adminInfo['id'] ?? 0;
        });

        Request::macro('adminInfo', function () use (&$adminInfo) {
            return $adminInfo;
        });
        Request::macro('roles', function () use (&$roles) {
            return $roles;
        });

        return $next($request);
    }
}


