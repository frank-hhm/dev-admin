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
    App,
    Config
};
use app\common\services\system\AdminService;
use app\common\services\common\CacheService;
use app\sys\validate\AdminValidate;
use app\sys\validate\AdminPassValidate;
/**
 * 账号
 * Class Admin
 * @package app\sys\controller\system
 */
class Admin extends Base
{
    /**
     * Admin constructor.
     */
    public function __construct(App $app , AdminService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 账号列表
     * @method(GET)
     */
    public function list()
    {
        $data = $this->request->getMore([
            ['account_like', ''],
            ['time', []],
        ]);
        $this->success($this->service->getAdminList($data));
    }

    /**
     * 添加账号
     * @method(POST)
     */
    public function create()
    {
        $data = $this->request->postMore([
            ['account', ''],
            ['real_name', ''],
            ['avatar',''],
            ['roles', []],
            ['pwd',''],
            ['conf_pwd',''],
        ]);
        validate(AdminValidate::class)->check($data);

        $this->service->create($data);
        $this->success('添加账号成功!');
    }

    /**
     * 修改账号
     * @method(PUT)
     */
    public function update()
    {
        $data = $this->request->postMore([
            ['id', ''],
            ['account', ''],
            ['real_name', ''],
            ['avatar',''],
            ['roles', []],
            ['pwd',''],
            ['conf_pwd','']
        ]);
        validate(AdminValidate::class)->check($data);

        $detail = $this->service->getDetail($data['id']);
        if(empty($detail)){
            $this->error('该账号不存在！');
        }
        if($detail['level'] == 0 && $this->adminId != $data['id']){
            $this->error('该账号不能编辑！');
        }
        if($detail['level'] == 1 && $this->adminId != $data['id']){
            $this->error('该账号不能编辑！');
        }
        if( $this->service->updateSave($data['id'],$data)){
            $this->success('修改账号成功!');
        }
        $this->error('修改账号失败!');
    }


    /**
     * 删除账号
     * @method(DELETE)
     */
    public function delete()
    {
        if (!$id = $this->request->param('id')) {
            $this->error('参数错误!');
        }
        $admin = $this->service->getOne(['id' => $id]);
        if (!$admin) {
            $this->error('该账号不存在！');
        }
        if (!$admin['level']) {
            $this->error('开发者不可删除！');
        }
        if ($admin['level'] == 1) {
            $this->error('超级账号不可删除！');
        }

        if ($this->service->destroy((int)$id)) {
            $this->success('删除成功!');
        }
        $this->error('删除失败!');
    }

    /**
     * 修改账号状态
     * @method(PUT)
     */
    public function status()
    {
        if (!$id = $this->request->param('id')) {
            $this->error('参数错误!');
        }
        $detail = $this->service->getDetail($id);
        if(empty($detail)){
            $this->error('该账号不存在！');
        }
        if($detail['level'] == 0 && $this->adminId != $id){
            $this->error('该账号不能修改状态！');
        }
        if($detail['level'] == 1 && $this->adminId != $id){
            $this->error('该账号不能修改状态！');
        }
        if ($this->service->update($id, ['status' => $this->request->param('status')])) {
            $this->success('修改成功');
        }
        $this->error('修改失败');
    }

    /**
     * 账号详细
     * @noAuth(true)
     * @method(GET)
     */
    public function detail(){
        $this->success($this->service->getDetail($this->request->get('id')));
    }


    /**
     * 账号详细
     * @noAuth(true)
     * @method(GET)
     */
    public function defaultDetail(){
        $this->success($this->adminInfo);
    }

    /**
     * 退出登陆
     * @noAuth(true)
     * @method(GET)
     */
    public function logout()
    {
        $key = trim(ltrim($this->request->header(Config::get('cookie.token_name')), 'Bearer'));
        CacheService::instance()->delete($key);
        $this->success('退出登陆成功');
    }

    /**
     * 修改密码
     * @noAuth(true)
     * @method(PUT)
     */
    public function updatePass()
    {
        $data = $this->request->postMore([
            ['old_pwd', ''],
            ['pwd', ''],
            ['conf_pwd', ''],
        ]);

        validate(AdminPassValidate::class)->check($data);

        if( $this->service->updatePass($data)){
            $this->success('修改密码成功!');
        }
        $this->error('修改密码失败!');
    }

}
