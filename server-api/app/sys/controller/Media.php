<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:31
 */
declare(strict_types=1);

namespace app\sys\controller;

use app\common\services\common\FileUploadCacheService;
use app\sys\controller\Base;
use think\facade\App;
use app\common\services\MediaService;
/**
 * 素材
 * Class Media
 * @package app\sys\controller\mate
 */
class Media extends Base
{
    /**
     * Media constructor.
     * @param App $app
     */
    public function __construct(App $app,MediaService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 文件列表
     * @noAuth(true)
     * @method(GET)
     */
    public function list()
    {
        $where = ['source'=>1];
        $data = $this->request->postMore([
            ['group_id',-1],
            ['page', 1],
            ['limit', ''],
            ['type', ''],
        ]);
        $data['group_id'] != -1 && $where['group_id'] = $data['group_id'];
        !empty($data['type']) && $where['file_type'] = $data['type'];
        $this->success($this->service->getPageList($where));
    }

    /**
     * 删除文件
     * @method(DELETE)
     */
    public function delete()
    {
        if ($this->service->destroy($this->request->param('ids'))) {
            $this->success('删除成功');
        }
        $this->error($this->service->getError() ?: '删除失败');
    }

    /**
     * 设置分组
     * @method(PUT)
     */
    public function setGroup()
    {
        if($this->service->updateGroup($this->request->param('ids'),$this->request->param('group_id'))){
            $this->success('修改成功!');
        }
        $this->error($this->service->getError() ?: '修改失败');
    }

    /**
     * 修改素材名称
     * @method(PUT)
     */
    public function updateName(){
        $data = $this->request->postMore([
            ['former_name', ''],
            ['id', 0],
        ]);
        if (!$data['former_name']) $this->error('请输入素材名称');
        $file = $this->service->getOne(['former_name' => $data['former_name']]);
        if ($file && $file['id'] != $this->request->param('id')) {
            $this->error('该素材名称已存在！请重新输入');
        }
        if($this->service->update($this->request->param('id'), $data)){
            $this->success('修改成功!');
        }
        $this->error('修改失败!');
    }

    /**
     * 上传素材
     * @method(POST)
     */
    public function uplaod(){
        $params = request()->param();
        $file = request()->file('file');
        if (empty($file)){
            $this->error('上传失败：文件为空!');
        }
        $res = $this->service->upload($file,$params);
        if($this->service->hasError()){
            $this->error($this->service->getError()?:"上传失败");
        }
        $this->success($res);
    }
}

