<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 12:37
 */
declare(strict_types=1);
namespace app\common\constants;

/**
 * @Constants
 */
class StatusCode
{
    const SUCCESS = [1,'请求成功'];

    const ERROR = [0,'失败'];

    const CAPTCHA_ERROR = [3,'验证码校验失败'];

    const ERR_LOGIN = [700,'请登录'];
}