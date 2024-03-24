<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:32
 */
declare(strict_types=1);

namespace app\sys\controller\system;

use app\sys\controller\Base;
use think\facade\{App, Config, Filesystem};
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
    protected array $seniorLevel = [-1,0];
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
        if(in_array($detail['level'],$this->seniorLevel) && $this->adminInfo['level'] > $detail['level']){
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
        $detail = $this->service->getDetail($id);
        if (!$detail) {
            $this->error('该账号不存在！');
        }
        if(in_array($detail['level'],$this->seniorLevel) && $this->adminInfo['level'] > $detail['level']){
            $this->error('该账号不能编辑！');
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
        if(in_array($detail['level'],$this->seniorLevel) && $this->adminInfo['level'] > $detail['level']){
            $this->error('该账号不能编辑！');
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
    public function detail(): void
    {
        $this->success($this->service->getDetail($this->request->get('id')));
    }


    /**
     * 账号详细
     * @noAuth(true)
     * @method(GET)
     */
    public function defaultDetail(): void
    {
        $this->success($this->adminInfo);
    }

    /**
     * 退出登陆
     * @noAuth(true)
     * @method(GET)
     */
    public function logout(): void
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
    public function updatePass(): void
    {
        $data = $this->request->postMore([
            ['old_pwd', ''],
            ['pwd', ''],
            ['conf_pwd', ''],
        ]);

        validate(AdminPassValidate::class)->check($data);

        if( $this->service->updatePass($data)){
            $this->success('修改密码成功!',[],701);
        }
        $this->error('修改密码失败!');
    }


    /**
     *
     */
    public function updateAvatar(){
        $file = request()->file('file');
        if (empty($file)){
            $this->error('上传失败：文件为空!');
        }
        $publicDisk = Filesystem::getDiskConfig('public');
        $saveFileName  = '/'.Filesystem::disk('public')->putFile('avatar', $file,'uniqid');
        $filePath = $publicDisk['url'] . $saveFileName;

        $fileUrl = sysconf("web_domain"). $filePath;
        if ($this->service->update($this->adminId, ['avatar' => $fileUrl])) {
            $this->success('修改成功');
        }
        $this->error('失败成功');
    }
}
