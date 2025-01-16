<?php
/**
 * @Date: 2025/1/15 14:02
 */
declare(strict_types=1);

namespace app\common\helper;

use app\common\exception\CommonException;
use app\common\services\common\CacheService;

/**
 * 操作邮箱发送帮助类
 * Class MailerHelper
 * @package app\common\helper
 */
class MailerHelper
{
    /**
     * 获取模板
     */
    public static function getTemplate(string $type,...$age): array
    {
        return match ($type) {
            'forget-password' => self::getCodeTemplate('找回密码',...$age),
            'again-email' => self::getCodeTemplate('重新绑定邮箱',...$age),
            default => [],
        };
    }

    /**
     * 校验验证码
     */
    public static function checkCode(string $email = '', string $code = ''): bool
    {
        $cacheService = app(CacheService::class);
        $cacheKey = 'verify_code:email:'.$email;
        if (!$cacheService->has($cacheKey)) {
            throw new CommonException('邮箱验证码错误,请先获取验证码!');
        }
        $res = !empty($code) && $cacheService->get('verify_code:email:'.$email) === $code;
        if(!$res) throw new CommonException('邮箱验证码错误!');
        return $cacheService->delete('verify_code:email:'.$email);
    }
    /**
     * 获取验证码模板
     * @param string $typeMsg
     * @param string $email
     * @param int $codeLength
     * @param int $expire
     * @return array
     */
    public static function getCodeTemplate(string $typeMsg = '绑定邮箱', string $email = '', int $codeLength = 6 ,int $expire = 60*10): array
    {
        $code = StringHelper::randString($codeLength);
        $cacheService = app(CacheService::class);
        $res = $cacheService->set('verify_code:email:'.$email,$code,$expire);
        $html = "";
        $html .= "<div style='width: 400px;margin: 0 auto;text-align: center;'>";
        $html .= "<div style='text-align: left'>";
        $html .= "<div style='margin-top: 30px;'>您好，您正在$typeMsg</div>";
        $html .= "<div style='margin-top: 10px;'>验证码是：<span style='font-weight: bold;'>$code</span></div>";
        $html .= "<div style='margin-top: 10px;'>验证有效时间为10分钟。请勿向他人提供验证码!</div>";
        $html .= "</div>";
        return [
            'title'=>'验证码',
            'html'=>true,
            'content'=>$html,
        ];
    }

}