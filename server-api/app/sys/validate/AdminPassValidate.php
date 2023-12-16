<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 15:55
 */
declare(strict_types=1);

namespace app\sys\validate;

use think\Validate;

class AdminPassValidate extends Validate
{
    protected $rule = [
        'old_pwd' => 'requireCallback:check_require|min:5',
        'pwd' => 'requireCallback:check_require|min:5',
        'conf_pwd' => 'requireCallback:check_require|confirm:pwd|min:5',
    ];
    protected $message  =   [
        'old_pwd.requireCallback'   => '请输入账号密码!',
        'old_pwd.min'   => '密码最小长度为6位!',
        'pwd.requireCallback'   => '请输入账号密码!',
        'conf_pwd.requireCallback'   => '请输入账号确定密码!',
        'pwd.min'   => '密码最小长度为6位!',
        'conf_pwd.min'   => '确定密码最小长度为6位!',
        'conf_pwd.confirm'   => '两次密码不一致!',
    ];

    protected function check_require($value, $data){
        return true;
    }
}
