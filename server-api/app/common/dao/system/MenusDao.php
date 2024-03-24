<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 12:39
 */

declare(strict_types=1);

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\MenusModel;

/**
 * 菜单dao层
 * Class MenusDao
 * @package app\common\dao\system
 */
class MenusDao extends BaseDao
{

    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return MenusModel::class;
    }

    /**
     * 获取权限菜单列表
     */
    public function getMenusRole(array $where, ?array $field = []): array|\think\Collection
    {
        if (!$field) {
            $field = ['id', 'menu_name', 'icon', 'pid', 'sort', 'type', 'params', 'menu_path', 'menu_node', 'status'];
        }
        return $this->model->where($where)->field($field)->order('sort DESC,id DESC')->select();
    }

    public function getMenusNode(array $where): array|\think\Collection
    {
        return $this->model->where($where)->where('menu_node','<>','')->order('sort DESC,id DESC')->select();
    }

    /**
     * 获取api列表
     */
    public function getMenusApiRule(array $where): array|\think\Collection
    {
        return $this->model->where($where)->where('api_rule','<>','')->order('sort DESC,id DESC')->select();
    }

    /**
     * 获取菜单列表并分页
     */
    public function getMenusList(array $where,$field = []): array
    {
        return $this->model->where($where)->order('sort DESC,id DESC')->field($field)->select()->toArray();
    }
}