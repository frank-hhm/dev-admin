<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:29
 */
declare(strict_types=1);

namespace app\common\services\common;

use app\common\exception\CommonException;
use app\common\services\BaseService;
use app\common\helper\StringHelper;
use ReflectionException;
use think\facade\Cache;
use think\facade\Filesystem;
use think\facade\Validate;
use think\File;

/**
 * 上传文件到缓存
 * Class NodeService
 * @package frank\services
 */
class FileUploadCacheService extends BaseService
{

    protected string $cacheKey = "uploads:cache:";
    public function uploadImage($file){
        $v = Validate::rule('img','fileSize:2097152|fileExt:jpg,png,gif,jpge,bmp|fileMime:image/gif,image/jpeg,image/bmp,image/png');
        if (!$v->check(['img'=>$file])) {
            throw new CommonException($v->getError());
        }
        $disk  = '/'.Filesystem::disk('cache')->putFile('', $file,'uniqid');
        $token = md5(uniqid(md5((string)microtime(true)), true));
        $rootDisk = Filesystem::getDiskConfig('cache');
        Cache::set($this->cacheKey.$token,$rootDisk['root'] . $disk);
        return ['file_token'=>$token,'file_url'=>  sysconf("web_domain").'/'.$rootDisk["url"].$disk ];
    }


    public function getCachePath($token){
        if(Cache::get($this->cacheKey.$token) === null) throw new CommonException('文件已过期');
        return Cache::get($this->cacheKey.$token);
    }

    public function getCacheFile($token){
        return new File(Cache::get($this->getCachePath($token)));
    }

    public function getCacheFileContent($token){
        return file_get_contents($this->getCachePath($token));
    }


    public function moveCache($tokenStr){
        $arr = explode(',', $tokenStr);
        $newArr = [];
        foreach ($arr as $key => $value) {
            if(Cache::get($this->cacheKey.$value) === null) throw new CommonException('文件已过期');
            $file = new File(Cache::get($this->cacheKey.$value));
            $rootDisk = Filesystem::getDiskConfig('cache');
            $path = $rootDisk['root'];

            !empty($path) && !file_exists($path) && mkdirs($path);
            $fileName = Filesystem::disk($rootDisk['storage'])->putFile('', $file,'uniqid');
            $newArr[]= sysconf("web_domain").'/'.$rootDisk['path'].$fileName;
        }
        return implode(',', $newArr);
    }
}
