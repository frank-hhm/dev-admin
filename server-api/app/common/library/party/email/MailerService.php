<?php
/**
 * @Date: 2025/1/14 18:00
 */
declare(strict_types=1);
namespace app\common\library\party\email;
use app\common\exception\CommonException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailerService
{
    protected mixed $mail;
    // 错误信息
    protected mixed $error;

    protected bool $from = false;
    public function __construct()
    {
        if(
            empty(sysconf('mail_imap_host'))||
            empty(sysconf('mail_imap_port'))||
            empty(sysconf('mail_username'))||
            empty(sysconf('mail_password'))||
            empty(sysconf('mail_default_from'))||
            empty(sysconf('mail_default_from_name'))
        ){
            throw new CommonException('请先配置邮件发送参数');
        }
        $mailHost = sysconf('mail_imap_host');
        $mailPost = sysconf('mail_imap_port');
        $mailUserName = sysconf('mail_username');
        $mailPassword = sysconf('mail_password');

        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = $mailHost; // SMTP 服务器地址
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $mailUserName; // SMTP 服务器的用户名
        $this->mail->Password = $mailPassword;  // SMTP 服务器的密码
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // 使用 SSL/TLS 加密连接
        $this->mail->Port = $mailPost; // SMTP 服务器的端口号
        $this->mail->CharSet = 'UTF-8'; // 设置发送的邮件的编码
    }
    public function send($subject = '测试邮件',$content = ''): bool
    {
        try {
            if(!$this->from){
                $defaultForm = sysconf('mail_default_from');
                $defaultFormName = sysconf('mail_default_from_name');
                $this->mail->setFrom($defaultForm, $defaultFormName);
            }
            $this->mail->Subject = $subject; // 邮件主题
            $this->mail->Body = $content; // 邮件内容
            // 发送邮件
            if ($this->mail->send()) {
                return true;
            }
            return false;
        }catch (\Exception $e){
            $this->error = $e->getMessage();
            return false;
        }
    }

    //发件人的邮箱地址和名称
    public function setFrom($address,$name): void
    {
        $this->from = true;
        try {
            $this->mail->setFrom($address, $name);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }
    public function setHtml($status = false): void
    {
        $this->mail->isHTML($status);
    }

    // 收件人的邮箱地址和名称
    public function addAddress($address,$name): void
    {
        try {
            $this->mail->addAddress($address, $name);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function addImage($imagePath): void
    {
        try {
            $this->mail->addEmbeddedImage($imagePath, 'logo');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }
    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError(): mixed
    {
        return $this->error;
    }

    /**
     * 是否存在错误
     * @return bool
     */
    public function hasError(): bool
    {
        return !empty($this->error);
    }

    /**
     * 设置错误信息
     * @param $error
     */
    public function setError($error): void
    {
        empty($this->error) && $this->error = $error;
    }
}