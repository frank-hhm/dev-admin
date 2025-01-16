<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:31
 */
declare(strict_types=1);

namespace app\sys\controller;
use app\BaseController;
use app\common\exception\CommonException;
use app\common\helper\MailerHelper;
use app\common\services\common\CacheService;
use app\common\services\common\CaptchaService;
use app\common\services\system\AdminService;
use app\common\services\system\LoginLogsService;
use app\sys\validate\AdminPassValidate;
use think\Cache;
use think\facade\Queue;

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
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? $this->request->ip();
        $cacheService = app(CacheService::class);
        $cacheKey = 'admin:login:'.$ip;
        $cacheCount = $cacheService->get($cacheKey);
        empty($cacheCount) && $cacheCount = 0;
        $cacheCount++;
        $cacheService->set($cacheKey, $cacheCount,15 * 60);
        if($cacheCount > 10){
            throw new CommonException('您登录次数过多，请稍后再试',703);
        }
        try {
            $data = $this->vali([
                'account.require' => '账号不能为空!',
                'account.min:3'   => '账号不能少于3位字符!',
                'password.require' => '登录密码不能为空!',
                'password.min:4'   => '登录密码不能少于4位字符!',
                'captcha_code.require'   => '图形验证码不能为空!',
                'captcha_uniqid.require'   => '图形验证标识不能为空!',
            ]);
            if(!CaptchaService::instance()->check($data['captcha_code'], $data['captcha_uniqid'])) {
                throw new CommonException('验证码错误，请重新输入',702);
            }

            $login = AdminService::instance()->login($data['account'], $data['password'], 'admin');
            throw new CommonException('',0);
        }catch (\Exception $e){
            $loginLogService = app(LoginLogsService::class);
            $adminId = $login['user_info']['id'] ?? 0;
            if(empty($adminId)){
                $adminId = AdminService::instance()->accountByAdminToId($data['account']);
            }
            $logData['admin_id'] = $adminId;
            $logData['source'] = 'admin';
            $logData['create_time'] = time();
            if($e->getCode() === 0 && !empty($login)){
                $loginLogService->createLog($logData,1,'登录成功');
                $this->success($login);
            }else {
                $loginLogService->createLog($logData,0,"登录失败","【{$e->getCode()}】:".$e->getMessage());
                $this->error($e->getMessage(),[],$e->getCode());
            }
        }
    }
    /**
     * 找回密码
     * @method(POST)
     */
    public function forgetPassword(): void
    {
        $data = $this->vali([
            'email.require'   => '请输入邮箱!',
            'email.email'   => '请输入正确的邮箱!',
            'code.require'   => '请输入验证码!',
            'code.min'   => '验证码不能少于6位字符!',
            'pwd.require'   => '请输入密码!',
            'conf_pwd.require'   => '请输入确定密码!',
            'pwd.min'   => '账号最小长度为6位!',
            'conf_pwd.min'   => '账号确定密码最小长度为6位!',
            'conf_pwd.confirm:pwd'   => '两次密码不一致!',
        ]);
        MailerHelper::checkCode($data['email'],$data['code']);

        $adminService = app(AdminService::class);
        $admin = $adminService->dao->model->where('email',$data['email'])->where('status',1)->find();
        if(empty($admin)){
            throw new CommonException('账号不存在!');
        }

        //修改密码
        $pwd = $adminService->dao->passwordHash($data['pwd']);
        if ($adminService->dao->update($admin['id'],['pwd'=>$pwd])) {
            $this->success('设置新密码成功');
        }
        $this->error('设置新密码失败');
    }
}
