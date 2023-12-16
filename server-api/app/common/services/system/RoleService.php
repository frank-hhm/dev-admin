<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:27
 */
declare(strict_types=1);

namespace app\common\services\system;

use app\common\dao\system\RoleDao;
use app\common\exception\CommonException;

/**
 * Class RoleService
 */
class RoleService extends \app\common\services\BaseService
{

    /**
     * 当前管理员权限缓存前缀
     */
    const ADMIN_RULES_LEVEL = 'Admin_rules_level_';

    /**
     * RoleService constructor.
     * @param RoleDao $dao
     */
    public function __construct(RoleDao $dao)
    {
        $this->dao = $dao;
    }

    public function getDetail($filter)
    {
        $detail = $this->dao->detail($filter);
        if (!$detail) {
            throw new CommonException('数据不存在');
        }
        return $detail->toArray();
    }


    /**
     * 获取角色列表
     */
    public function getPageList(array $where)
    {
        [$page, $limit] = $this->dao->getPageValue();
        $list = $this->dao->getPageList($where,$page, $limit);
        return $list;
    }

    /**
     * 获取权限
     */
    public function getRoleArray(array $where = [], string $field = '', string $key = '')
    {
        return $this->dao->getRoule($where, $field, $key);
    }

    /**
     * 获取数据
     */
    public function selectList(array $where = [])
    {
        $list = $this->dao->selectList($where);
        if(!$list){
            return [];
        }
        return $list->toArray();
    }
}

