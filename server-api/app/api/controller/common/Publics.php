<?php
/**
 * @Date: 2025/1/14 17:52
 */
declare(strict_types=1);

namespace app\api\controller\common;

use app\common\enum\EnumFactory;
use app\common\exception\CommonException;
use app\common\helper\MailerHelper;
use app\common\helper\StringHelper;
use app\common\library\party\email\MailerService;
use app\common\services\common\CacheService;
use app\common\services\common\CaptchaService;

/**
 * Class Publics
 */
class Publics extends \app\api\controller\Base
{

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

    /**
     * 获取邮箱验证码
     * @method(POST)
     */
    public function getEmailCode(): void
    {
        $data = $this->request->postMore([
            ['email', ''],
            ['type', ''],
            ['captcha_code', ''],
            ['captcha_uniqid', '']
        ]);

        if(!CaptchaService::instance()->check($data['captcha_code'], $data['captcha_uniqid'])) {
            throw new CommonException('验证码错误，请重新输入',702);
        }

        $mailService = app(MailerService::class);
        $mailService->addAddress($data['email'], $data['email']);
        $tempRes = MailerHelper::getTemplate($data['type'],$data['email']);
        !empty($tempRes['html']) && $mailService->setHtml(true);
        // 发送邮件
        if ($mailService->send($tempRes['title'], $tempRes['content'])) {
            $this->success('发送成功');
        }

        $this->error($mailService->getError() ?: '发送失败');
    }
}