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
        $keyArr = [
            'system_name',
            'system_version',
            'system_logo',
            'system_icon',
            'web_domain',
            "copyright",
        ];
        $data = [];
        foreach ($keyArr as $key){
            $data[$key] = sysconf($key);
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