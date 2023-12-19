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


    /**
     * 文件名
     */
    public function searchFileNameAttr($query, $value)
    {
        if ($value) {
            $query->whereLike('file_name', '%' . $value . '%');
        }
    }

    /**
     * 分组
     */
    public function searchGroupIdAttr($query, $value)
    {
        if ($value) {
            $query->where('group_id', $value);
        }
    }


    /**
     * 来源
     */
    public function searchSourceIdAttr($query, $value)
    {
        if ($value) {
            $query->where('source_id', $value);
        }
    }


    /**
     * 来源
     */
    public function searchSourceAttr($query, $value)
    {
        if ($value) {
            $query->where('source', $value);
        }
    }

    public function searchTypeAttr($query, $value)
    {
        if ($value) {
            $query->where('type', $value);
        }
    }

    /**
     * 类型
     */
    public function searchFileTypeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('file_type', $value);
        }
    }
}