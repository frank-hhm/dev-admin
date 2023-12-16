<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:21
 */

declare(strict_types=1);

namespace app\common\middleware;

use app\common\middleware\MiddlewareInterface;
use app\Request;
use Closure;
use think\facade\Config;
use think\Response;

/**
 * 跨域中间件
 * Class AllowOriginMiddleware
 * @package frank\middleware
 */
class AllowOriginMiddleware implements MiddlewareInterface
{

    /**
     * 允许跨域的域名
     * @var string
     */
    protected string $cookieDomain;

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->cookieDomain = Config::get('cookie.domain', '');
        $header = Config::get('cookie.header');
        if(!empty($header)){
            $origin = $request->header('origin');
            if ($origin && ('' == $this->cookieDomain || strpos($origin, $this->cookieDomain))){
                $header['Access-Control-Allow-Origin'] = $origin;
            }
        }else{
            $header = [];
        }
        if ($request->method(true) == 'OPTIONS') {
            $response = Response::create('ok')->code(200)->header($header);
        } else {
            $response = $next($request)->code(200)->header($header);
        }
        return $response;
    }
}
