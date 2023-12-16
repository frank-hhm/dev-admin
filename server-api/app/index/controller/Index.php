<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:32
 */
declare(strict_types=1);
namespace app\index\controller;

use app\BaseController;
use app\common\enum\EnumFactory;
use app\common\services\common\CaptchaService;
use think\facade\Request;

class Index extends BaseController
{
    public function index()
    {
        $request = Request::instance();
        header('Location:' . $request->domain() . '/admin');
        exit();
    }


    /**
     * 生成验证码
     * @method(GET)
     */
    public function captcha()
    {
        $captcha = CaptchaService::instance()->initialize()->getAttrs();
        $this->success('生成验证码成功', $captcha);
    }
    /**
     * 获取系统信息
     * @method(GET)
     */
    public function systemInfo()
    {
        $data['system_name'] = sysconf('system_name');
        $data['system_version'] = sysconf('system_version');
        $data['system_logo'] = sysconf('system_logo');
        $data['system_icon'] = sysconf('system_icon');
        $data['copyright'] = sysconf('copyright');
        $this->success($data);
    }

    /**
     * 获取系统权举数据
     * @method(GET)
     */
    public function enum()
    {
        $this->success(EnumFactory::getAll());
    }
}