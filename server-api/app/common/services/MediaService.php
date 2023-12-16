<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:25
 */
declare(strict_types=1);

namespace app\common\services;

use app\common\dao\MediaDao;
use app\common\exception\CommonException;
use think\facade\Filesystem;
/**
 * 素材
 * Class MediaService
 */
class MediaService extends BaseService
{

    /**
     * MediaService constructor.
     * @param MediaDao $dao
     */
    public function __construct(MediaDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 获取列表
     */
    public function getPageList(array $where = [])
    {
        [$page, $limit] = $this->getPageValue();
        return $this->dao->getPageList($where,$page, $limit);
    }

    public function updateGroup($ids,$groupId){
        return $this->dao->model->whereIn('id',$ids)->update(['group_id'=>$groupId]);
    }


    public function upload($file,$params = []){
        try {
            $originalName = $file->getOriginalName();//文件原名

            $publicDisk = Filesystem::getDiskConfig('public');

            $saveFileName  = '/'.Filesystem::disk('public')->putFile('media', $file,'uniqid');

            $fileName = basename($saveFileName, "." . 1);

            $filePath = $publicDisk['url'] . $saveFileName;


            $fileRootPath = $publicDisk['root'].$filePath;

            $fileUrl = sysconf("web_domain"). $filePath;

            $fileInfo = [
                'oss_type'  => 'local',
                'file_path' =>$filePath,
                'file_size' => $file->getSize(),
                'file_name' => $fileName,
                'former_name' => $originalName,
                'file_type' => substr($file->getMime(),0,strrpos($file->getMime(),"/")),
                'extension' => substr($file->getMime(),strripos($file->getMime(),"/")+1),
                'file_domain'=>sysconf("web_domain"),
                'create_time'=>time(),
                'group_id'=>$params[ "group_id" ] ?? 0,
                'source_id'=>$params[ "source_id" ] ?? 0,
                'source'=>$params[ "source" ] ?? 1,
                "file_url" => $fileUrl
            ];

            $this->dao->create($fileInfo);

            return [
                "file_url" => $fileUrl
            ];
        }catch (\Exception $e){
            $this->error = $e->getMessage();
            return [];
        }
    }
}

