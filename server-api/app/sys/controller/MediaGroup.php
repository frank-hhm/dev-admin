<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:31
 */
declare(strict_types=1);

namespace app\sys\controller;

use app\sys\controller\Base;
use think\facade\{
    App
};
use app\common\services\MediaGroupService;
/**
 * 素材-分组
 * Class MediaGroup
 * @package app\sys\controller
 */
class MediaGroup extends Base
{
    /**
     * MediaGroup constructor.
     * @param App $app
     */
    public function __construct(App $app,MediaGroupService $service)
    {
        parent::__construct($app);
        $this->service = $service;

    }

    /**
     * 文件分组列表
     * @noAuth(true)
     * @method(GET)
     */
    public function list()
    {
        $this->success($this->service->selectList([
            'source'=>1,
            'source_id'=>$this->adminId
        ]));
    }

    /**
     * 添加文件分组
     * @method(POST)
     */
    public function create()
    {
        $data = $this->request->postMore([
            ['group_name', '']
        ]);
        $group = $this->service->getOne(['group_name' => $data['group_name']]);
        if ($group) {
            $this->error('该分组名称已存在！请重新输入');
        }
        $data['source_id'] = $this->adminId;
        $data['source'] = 1;
        if( $this->service->save($data)){
            $this->success('添加分组成功!');
        }
        $this->error('添加分组失败!');
    }

    /**
     * 编辑文件分组
     * @method(PUT)
     */
    public function update()
    {
        $data = $this->request->postMore([
            ['group_name', ''],
        ]);
        if (!$data['group_name']) $this->error('请输入分组名称');
        $group = $this->service->getOne(['group_name' => $data['group_name']]);
        if ($group && $group['id'] != $this->request->param('id')) {
            $this->error('该分组名称已存在！请重新输入');
        }
        if($this->service->update($this->request->param('id'), $data)){
            $this->success('修改成功!');
        }
        $this->error('修改失败!');
    }


    /**
     * 删除文件分组
     * @method(DELETE)
     */
    public function delete()
    {
        if (!$groupId = $this->request->param('group_id')) {
            $this->error('参数错误!');
        }

        if ($this->service->delete((int)$groupId)) {
            $this->success('删除成功!');
        }
        $this->error('删除失败!');
    }
}

