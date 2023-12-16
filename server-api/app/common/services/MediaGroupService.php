<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:25
 */
declare(strict_types=1);

namespace app\common\services;

use app\common\dao\MediaGroupDao;

/**
 * 素材分组
 * Class MediaGroupService
 */
class MediaGroupService extends BaseService
{

    /**
     * MediaGroupService constructor.
     * @param MediaGroupDao $dao
     */
    public function __construct(MediaGroupDao $dao)
    {
        $this->dao = $dao;
    }

    public function selectList($where = []){
        $select = $this->dao->selectList($where);
        if(!$select) return [];
        return $select->toArray();
    }
}



