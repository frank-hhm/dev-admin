<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 12:38
 */

declare(strict_types=1);

namespace app\common\dao\system;

use app\common\model\system\AdminModel;

/**
 * Class AdminDao
 * @package app\common\dao\system
 */
class AdminDao extends \app\common\dao\BaseDao
{
    protected array $field = ['*'];

    protected function setModel(): string
    {
        return AdminModel::class;
    }

    /**
     * 获取列表
     */
    public function getAdminList(array $where, int $page, int $limit, $with = []): array
    {
        return $this->model->where($where)->when(count($with), function ($query) use ($with) {
            $query->with($with);
        })->order(['create_time DESC'])->page($page)->paginate($limit)->toArray();
    }

    /**
     * 用管理员名查找管理员信息
     */
    public function accountByAdmin(string $account)
    {
        return $this->model->where(['account|email' => $account, 'status' => 1])->find();
    }

    public function accountByAdminToId(string $account)
    {
        return $this->model->where(['account|email' => $account, 'status' => 1])->value('id');
    }

    public function accountById(int $id)
    {
        return $this->model->where(['id' => $id])->field($this->field)->find($id);
    }

    /**
     * 当前账号是否可用
     */
    public function isAccountUsable($account, $id)
    {
        return $this->model->where(['account|email' => $account])->where('id', '<>', $id)->count();
    }

    /**
     * 获取admin
     */
    public function getAdminIds(int $level)
    {
        return $this->model->where('level', '>=', $level)->column('id', 'id');
    }

    /**
     * 获取低于等级的管理员名称和id
     */
    public function getOrdAdmin(string $field = 'real_name,id', int $level = 0)
    {
        return $this->model->where('level', '>=', $level)->field($field)->order('sort DESC,id DESC')->select()->toArray();
    }

    /**
     * 条件获取管理员数据
     */
    public function getInfo($where)
    {
        return $this->model->where($where)->find();
    }
}

