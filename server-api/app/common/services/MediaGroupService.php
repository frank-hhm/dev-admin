<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:25
 */
declare(strict_types=1);

namespace app\common\services;

use app\common\dao\MediaGroupDao;
use app\common\helper\ArrayHelper;

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
        $select = $this->dao->selectList($where)->toArray();
        $select = ArrayHelper::sortListTier($select);
        return $select;
    }
    /**
     * 获取级联
     */
    public function getCascader($params,$value = ''): array
    {
        $list = $this->dao->model->where($params)->field(['id as value', 'pid', 'group_name as label'])->select();
        $list = $this->getListData($list);
        if (!empty($value)) {
            $data = ArrayHelper::getArrayTreeValue($list, $value);
            foreach ($list as $key => &$item) {
                if(in_array($item['value'] , $data) || $item['pid'] == $value) $item['disabled'] = true;
            }
        } else {
            $data = [];
        }
        return [ArrayHelper::getArrayTreeChildren($list, 'children', 'value'), array_reverse($data)];
    }

    /**
     * 获取没有被修改器修改的数据
     */
    public function getListData($list): array
    {
        $data = [];
        foreach ($list as $item) {
            $data[] = $item->getData();
        }
        return $data;
    }
}



