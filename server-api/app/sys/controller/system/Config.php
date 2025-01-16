<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:32
 */
declare(strict_types=1);

namespace app\sys\controller\system;

use app\sys\controller\Base;
/**
 * 配置
 */
class Config extends Base
{
    /**
     * 获取配置
     * @noAuth(true)
     * @method(GET)
     */
    public function get(){
        $type = $this->request->param('type');
        $keyArr = [
            'default' => [
                'system_name',
                'system_version',
                'system_logo',
                'system_icon',
                'web_domain',
                "copyright",
            ],
            'email' => [
                'mail_imap_host',
                'mail_imap_port',
                'mail_username',
                'mail_password',
                'mail_default_from',
                'mail_default_from_name',
            ],
        ];
        $source = explode('.', $type);
        $keyArr2 = [];
        foreach ($source as $item) {
            if (isset($keyArr[$item])) {
                $keyArr2 = $keyArr[$item];
            }
        }
        $data = [];
        foreach ($keyArr2 as $key) {
            $value = sysconf($key);
            $data[$key] = is_null(json_decode($value)) ? $value : json_decode($value, true);
        }
        $this->success($data);
    }
    /**
     * 保存配置
     * @method(POST)
     */
    public function save(){
        $data = $this->request->post();
        foreach ($data as $key => $value) {
            sysconf($key, $value);
        }
        $this->success('保存成功！');
    }
}