<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:27
 */
declare(strict_types=1);


namespace app\common\services\system;

use app\common\exception\CommonException;
use app\common\dao\system\MenusDao;
use app\common\services\system\RoleService;
use app\common\helper\ArrayHelper;

/**
 * 权限菜单
 * Class MenusService
 */
class MenusService extends \app\common\services\BaseService
{

    /**
     * MensServices constructor.
     * @param MenusDao $dao
     */
    public function __construct(MenusDao $dao)
    {
        $this->dao = $dao;
    }

    public function getMenuDetail($filter)
    {
        $detail = $this->dao->detail($filter);
        if (!$detail) {
            throw new CommonException('数据不存在');
        }
        return $detail->toArray();
    }

    /**
     * 获取菜单树型结构列表
     */
    public function getList(array $params,$field = '*'): array
    {
        $filter = [];
        if(!empty($params['status'])){
            $filter[] = ['status','=',$params['status']];
        }
        $menusList = $this->dao->getMenusList($filter,$field);
        $menusList = ArrayHelper::sortListTier($menusList);
        return ArrayHelper::getArrayTreeChildren($menusList);
    }

    public function getRoleStr($roleId): array
    {
        $roleService = RoleService::instance();
        $rules = $roleService->getRoleArray([['status','=',1,],['id','in', $roleId]], 'rules');
        $rulesStr = ArrayHelper::unique($rules);
        return $rulesStr;
    }

    /**
     * 获取后台权限菜单和权限
     */
    public function getMenusList($roleId, int $level,int $type = 1): array
    {
        $rulesStr = $this->getRoleStr($roleId);
        $ruleMap = ['status' => 1];
        $level > 0 && $ruleMap['id'] = $rulesStr;
        $menusList = $this->dao->getMenusRole(array_merge($ruleMap,['type' => $type]));
        $menusList && $menusList = $menusList->toArray();
        $menusList = ArrayHelper::arr2tree($menusList,'id','pid','children');
        $action = $level > 0 ? ArrayHelper::getArrayColumn($this->dao->getMenusNode($ruleMap),'menu_node') : -1;
        if($type == 2){
            $addonMenus = [];
            foreach ($menusList as $key => $value) {
                $addonMenus[$value['menu_path']] = $value['children'] ?? [];
            }
            return $addonMenus;
        }
        return ['menusList'=>$menusList,'action'=>  $action,'homePath'=>$this->getHome($menusList)['menu_path'] ?? ''];
    }

    /**
     * 获取接口
     */
    public function getApiList($ruleId, int $level): array
    {
        $rulesStr = $this->getRoleStr($ruleId);
        $ruleMap = ['status' => 1];
        $level > 0 && $ruleMap['id'] = $rulesStr;
        $menusList = $this->dao->getMenusApiRule($ruleMap);
        $menusList && $menusList = $menusList->toArray();
        $menusApi = ArrayHelper::getArrayColumn($menusList,'api_rule');
        return $menusApi;
    }

    /**
     * 获取菜单级联
     */
    public function getCascaderMenus($value = ''): array
    {
        $menuList = $this->dao->getMenusRole(['delete_time' => 0], ['id as value', 'pid', 'menu_name as label']);
        $menuList = $this->getMenusData($menuList);
        if (!empty($value)) {
            $data = ArrayHelper::getArrayTreeValue($menuList, $value);
            foreach ($menuList as $key => &$item) {
                if(in_array($item['value'] , $data) || $item['pid'] == $value) $item['disabled'] = true;
            }
        } else {
            $data = [];
        }
        return [ArrayHelper::getArrayTreeChildren($menuList, 'children', 'value'), array_reverse($data)];
    }

    /**
     * 获取菜单没有被修改器修改的数据
     */
    public function getMenusData($menusList): array
    {
        $data = [];
        foreach ($menusList as $item) {
            $data[] = $item->getData();
        }
        return $data;
    }



    /**
     * 获取首页
     */
    public function getHome($menusList){
        $menu = [];
        foreach ($menusList as $key => $item) {
            $menu = $item;
            if(!empty($item['children'])){
                $menu = $this->getHome($item['children']);
            }
            if(!empty($menu['menu_path'])){
                break;
            }
        }
        return $menu;
    }
}
