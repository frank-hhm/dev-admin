<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:20
 */

declare(strict_types=1);

namespace app\common\exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\facade\View;
use think\facade\Request;
use think\Response;
use Throwable;
use Exception;

/**
 * Class CommonException
 * 自定义异常类的基类
 */
class CommonException extends \RuntimeException
{
    public function __construct($message = '',$code = -1, Exception $previous = null)
    {
        if (is_array($message)) {
            $errInfo = $message;
            $message = $errInfo[1] ?? '未知错误';
            if ($code === -1) {
                $code = $errInfo[0] ?? 1;
            }
        }
        parent::__construct($message, $code, $previous);
    }
}