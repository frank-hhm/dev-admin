<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 15:55
 */
declare(strict_types=1);

namespace app\sys\middleware;

use app\Request;
use app\common\middleware\MiddlewareInterface;

/**
 * Class BaseMiddleware
 * @package app\sys\middleware
 */
class BaseMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next, bool $force = true)
    {
        if (!Request::hasMacro('adminId')) {
            Request::macro('adminId', function(){ return 0; });
        }
        return $next($request);
    }
}

