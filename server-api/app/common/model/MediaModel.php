<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:22
 */

declare(strict_types=1);

namespace app\common\model;

use app\common\traits\ModelTrait;
use think\facade\Request;

/**
 * 素材
 * Class MediaModel
 * @package app\common\model
 */
class MediaModel extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     */
    protected $pk = 'id';

    /**
     * 模型名称
     */
    protected $name = 'media';

    protected $append = [
        'file_url'
    ];

    public function getFileUrlAttr($value,$data){
        $domain = ($data['oss_type'] == 'local'?sysconf("web_domain"):$data['file_domain']);
        return $domain.$data['file_path'];
    }
}