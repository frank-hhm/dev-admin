<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:24
 */

declare(strict_types=1);

namespace app\common\model\system;

use app\common\model\BaseModel;
use app\common\traits\ModelTrait;

/**
 * 管理员权限规则
 * Class RoleModel
 * @package app\common\model\system
 */
class RoleModel extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     */
    protected $pk = 'id';

    /**
     * 模型名称
     */
    protected $name = 'system_role';

    /**
     * 规则修改器
     */
    public static function setRulesAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    public static function getRulesAttr($value): array
    {
        return !empty($value) ? explode(',', $value) : [];
    }
}

