<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 15:55
 */
declare(strict_types=1);

namespace app\sys\validate;

use think\Validate;

class AdminValidate extends Validate
{
    protected $rule = [
        'account' => 'require|alphaDash',
        'real_name' => 'require',
        'pwd' => 'requireCallback:check_require|min:5',
        'conf_pwd' => 'requireCallback:check_require|confirm:pwd|min:5',
    ];
    protected $message  =   [
        'account.require' => '请输入账号!',
        'real_name.alphaDash'     => '账号格式错误（字母和数字，下划线及破折号）!',
        'real_name.require'     => '请输入账号姓名!',
        'pwd.requireCallback'   => '请输入账号密码!',
        'conf_pwd.requireCallback'   => '请输入账号确定密码!',
        'pwd.min'   => '账号最小长度为6位!',
        'conf_pwd.min'   => '账号确定密码最小长度为6位!',
        'conf_pwd.confirm'   => '两次密码不一致!',
    ];

    protected function check_require($value, $data){
        if(empty($data['id']) || !empty($data['pwd'])|| !empty($data['conf_pwd'])){
            return true;
        }
    }

    protected function roles_check_require($value, $data){
        if(empty($data['id'])){
            return true;
        }
    }
}
