<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:20
 */

declare(strict_types=1);

namespace app\common\exception;
use think\exception\Handle;
use think\Response;
use Throwable;

/**
 * Class ApiException
 * 自定义异常类
 */
class ApiException extends Handle
{
    public function render($request, Throwable $e): Response
    {
        if(method_exists($e, 'getResponse')){
            return parent::render($request, $e);
        }
        $result = [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'data' => [],
        ];
        if(env('app_debug') === true){
            return parent::render($request, $e);
        }
        return json($result);
    }
}