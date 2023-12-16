<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:23
 */

declare(strict_types=1);

namespace app\common\model\system;

use app\common\helper\ArrayHelper;
use app\common\model\BaseModel;
use app\common\traits\ModelTrait;
use think\model\concern\SoftDelete;
use app\common\enum\EnumFactory;

/**
 * 菜单规则模型
 * Class MenusModel
 * @package app\common\model\system
 */
class MenusModel extends BaseModel
{
    use ModelTrait;

    use SoftDelete;

    /**
     * 数据表主键
     */
    protected $pk = 'id';

    protected string $deleteTime = 'delete_time';

    protected $defaultSoftDelete = 0;

    /**
     * 模型名称
     */
    protected $name = 'system_menus';

    protected $append = ['url_params'];

    /**
     * 状态获取器
     */
    public function getStatusAttr($value)
    {
        return EnumFactory::instance('status')->getItem($value);
    }

    /**
     * 最佳参数字段数据结果
     */
    public function getUrlParamsAttr($value, $data)
    {
        return ArrayHelper::urlParamsToArray($data['params'] ?? '');
        // !empty($data['params']) ? implode('&', $data['params'])
    }

    /**
     * 状态搜索器
     */
    public function searchStatusAttr($query, $value)
    {
        if ($value != '') {
            $query->where('status', $value ?: 1);
        }
    }

    /**
     * 类型搜索器
     */
    public function searchTypeAttr($query, $value)
    {
        if ($value != '') {
            $query->where('type', $value);
        }
    }


    /**
     * id搜索器
     */
    public function searchIdAttr($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('id', $value);
        } else {
            $query->where('id', $value);
        }
    }
}