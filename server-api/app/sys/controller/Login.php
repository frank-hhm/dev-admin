<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:31
 */
declare(strict_types=1);

namespace app\sys\controller;
use app\BaseController;
use app\common\services\common\CaptchaService;
use app\common\services\system\AdminService;
/**
 * 登陆
 * @NoMiddleware("app\sys\middleware\AuthMiddleware")
 */
class Login extends BaseController
{
    /**
     * 登陆授权
     * @method(POST)
     */
    public function index()
    {
        $data = $this->vali([
            'account.require' => '账号不能为空!',
            'account.min:3'   => '账号不能少于3位字符!',
            'password.require' => '登录密码不能为空!',
            'password.min:4'   => '登录密码不能少于4位字符!',
            'captcha_code.require'   => '图形验证码不能为空!',
            'captcha_uniqid.require'   => '图形验证标识不能为空!',
        ]);

        if(!CaptchaService::instance()->check($data['captcha_code'], $data['captcha_uniqid'])) {
            $this->error('验证码错误，请重新输入',[],701);
        }
        $this->success(AdminService::instance()->login($data['account'], $data['password'], 'admin'));
    }
}
