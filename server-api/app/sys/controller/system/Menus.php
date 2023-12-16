<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:32
 */
declare(strict_types=1);

namespace app\sys\controller\system;
use app\sys\controller\Base;
use think\facade\{
    App
};
use app\common\services\common\NodeService;
use app\common\services\system\MenusService;

/**
 * 菜单
 * Class Menus
 * @package app\sys\controller\system
 */
class Menus extends Base
{
    /**
     * Menus constructor.
     * @param App $app
     * @param MenusService $service
     */
    public function __construct(App $app, MenusService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 菜单列表
     * @method(GET)
     */
    public function list()
    {
        $where = $this->request->getMore([
            ['status', ''],
        ]);
        $data = $this->service->getMenusList($this->adminInfo['roles'], (int)$this->adminInfo['level']);
        $data['list']= $this->service->getList($where);
        $data['addon_menus']= $this->service->getMenusList($this->adminInfo['roles'], (int)$this->adminInfo['level'],2);
        $this->success($data);
    }

    /**
     *菜单详细
     * @noAuth(true)
     * @method(GET)
     */
    public function detail(){
        $this->success($this->service->getMenuDetail($this->request->get('id')));
    }

    /**
     * 添加菜单
     * @method(POST)
     */
    public function create(){
        $data = $this->request->getMore([
            ['menu_name', ''],
            ['menu_path', ''],
            ['menu_node', ''],
            ['icon', ''],
            ['type', 1],
            ['sort', 0],
            ['pid', 0],
            ['status', 0],
            ['id', 0],
            ['api_rule','']
        ]);
        if (!$data['menu_name'])
            $this->error('请输入菜单名称');
        if ($this->service->save($data)){
            $this->success('保存成功');
        }
        $this->error('保存失败');
    }

    /**
     * 编辑菜单
     * @method(PUT)
     */
    public function update(){
        $data = $this->request->getMore([
            ['menu_name', ''],
            ['menu_path', ''],
            ['menu_node', ''],
            ['params',''],
            ['icon', ''],
            ['type', 1],
            ['sort', 0],
            ['pid', 0],
            ['status', 0],
            ['id', 0],
            ['api_rule','']
        ]);
        if (!$data['menu_name'])
            $this->error('请输入菜单名称');
        if ($this->service->update($data['id'], $data)){
            $this->success('修改成功');
        }
        $this->error('修改失败');
    }


    /**
     * 删除菜单
     * @method(DELETE)
     */
    public function delete()
    {
        $id = $this->request->param('id');
        if (!$id) {
            $this->error('参数错误!');
        }

        if ($this->service->destroy((int)$id,true)) {
            $this->success('删除成功!');
        }
        $this->error('删除失败!');
    }

    /**
     * 修改菜单状态
     * @method(PUT)
     */
    public function status()
    {
        if (!$id = $this->request->param('id')) {
            $this->error('参数错误!');
        }
        if ($this->service->update($id, ['status' => $this->request->param('status')])) {
            $this->success('修改成功');
        }
        $this->error('修改失败');
    }

    /**
     * 获取菜单级联
     * @noAuth(true)
     * @method(GET)
     */
    public function getCascader(){
        $pid = $this->request->get('pid',0);
        [$menuList, $ids] = $this->service->getCascaderMenus($pid);
        $this->success(compact('menuList','ids'));
    }

    /**
     * 获取接口列表
     * @noAuth(true)
     * @method(GET)
     */
    public function ruleList()
    {
        //获取所有的路由
        $ruleList = NodeService::instance()->getList();
        $this->success($ruleList);
    }
}
