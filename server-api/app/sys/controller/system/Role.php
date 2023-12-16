<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:32
 */
declare(strict_types=1);

namespace app\sys\controller\system;
use app\sys\controller\Base;
use think\facade\App;
use app\common\services\system\RoleService;
use app\common\services\system\MenusService;

/**
 * 权限角色管理
 * Class Role
 * @package app\sys\controller\system
 */
class Role extends Base
{
    /**
     * Role constructor.
     * @param App $app
     * @param RoleService $service
     */
    public function __construct(App $app, RoleService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 角色列表
     * @method(GET)
     */
    public function list()
    {
        $data = $this->request->getMore([
        ]);
        $this->success($this->service->getPageList($data));
    }

    /**
     * 角色详细
     * @noAuth(true)
     * @method(GET)
     */
    public function detail(){
        $this->success($this->service->getDetail($this->request->get('id')));
    }

    /**
     * 添加角色
     * @method(POST)
     */
    public function create(){
        $data = $this->request->getMore([
            ['role_name', ''],
            ['remarks', ''],
        ]);
        if (!$data['role_name'])
            $this->error('请输入角色名称');
        if ($this->service->save($data)){
            $this->success('保存成功');
        }
        $this->error('保存失败');
    }

    /**
     * 编辑角色
     * @method(PUT)
     */
    public function update()
    {
        if (!$id = $this->request->param('id')) {
            $this->error('参数错误!');
        }
        $data = $this->request->postMore([
            ['role_name', ''],
            ['remarks', ''],
        ]);
        if (!$data['role_name']) $this->error('请输入角色名称');
        if($this->service->update($id, $data)){
            $this->success('修改成功!');
        }
        $this->error('修改失败!');
    }

    /**
     * 删除角色
     * @method(DELETE)
     */
    public function delete()
    {
        if (!$id = $this->request->param('id')) {
            $this->error('参数错误!');
        }

        if ($this->service->delete((int)$id)) {
            $this->success('删除成功!');
        }
        $this->error('删除失败!');
    }


    /**
     * 获取权限节点列表
     * @noAuth(true)
     * @method(GET)
     */
    public function getRule(){
        $this->success(MenusService::instance()->getList(['status'=>1],'id,pid,menu_name as label'));
    }

    /**
     * 获取校色数据
     * @noAuth(true)
     * @method(GET)
     */
    public function getSelectList(){
        $this->success($this->service->selectList(['status'=>1]));
    }

    /**
     * 保存授权设置
     * @method(PUT)
     */
    public function saveRules()
    {
        $data = $this->request->postMore([
            ['rules', ''],
            ['id', ''],
        ]);
        if($this->service->update($this->request->param('id'), $data)){
            $this->success('保存成功!');
        }
        $this->error('保存失败!');
    }
}
