<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 12:39
 */

declare(strict_types=1);

namespace app\common\dao\system;

use app\common\model\system\RoleModel;

/**
 * Class RoleDao
 * @package app\common\dao\system
 */
class RoleDao extends \app\common\dao\BaseDao
{
    /**
     * 设置模型名
     */
    protected function setModel(): string
    {
        return RoleModel::class;
    }

    /**
     * 获取权限
     */
    public function getRoule(array $where = [], ?string $field = null, ?string $key = null)
    {
        return $this->model->where($where)->column($field ?: 'role_name', $key ?: 'id');
    }

    /**
     * 获取身份列表
     */
    public function getRouleList(array $where, int $page, int $limit)
    {
        return $this->model->where($where)->page($page)->paginate($limit)->toArray();
    }
}
