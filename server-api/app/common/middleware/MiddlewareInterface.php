<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:22
 */

declare(strict_types=1);

namespace app\common\middleware;

use app\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, \Closure $next);
}
