<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:28
 */
declare(strict_types=1);


namespace app\common\services\common;

use app\common\exception\CommonException;
use app\common\services\BaseService;
use think\facade\Cache as CacheStatic;
use think\facade\Config;

/**
 * 缓存类
 * Class CacheService
 */
class CacheService  extends BaseService
{

    ## 过期时间
    protected int $expire = 0;

    ## 获取缓存过期时间
    protected function getExpire(int $expire = null): int
    {
        if ($this->expire) {
            return (int)$this->expire;
        }
        $default = Config::get('cache.default');
        $expire = Config::get('cache.' . $default . '.expire');
        if (!is_int($expire))
            $expire = (int)$expire;

        return $this->expire = $expire;
    }

    ## 写入缓存
    public function set(string $name, $value, int $expire = null): bool
    {
        try {
            return $this->redis()->set($name, $value, $expire ?? $this->getExpire($expire));
        } catch (\Throwable $e) {
//            throw new CommonException($e->getMessage());
            return false;
        }
    }

    public function get(string $name)
    {
        try {
            return $this->redis()->get($name);
        } catch (\Throwable $e) {
            throw new CommonException($e->getMessage());
        }
    }

    ## 查看是否存在
    public function has(string $key)
    {
        try {
            return $this->redis()->has($key);
        } catch (\Exception $e) {
            throw new CommonException($e->getMessage());
        }
    }

    ## 删除缓存
    public function delete(string $name): bool
    {
        return CacheStatic::delete($name);
    }

    ## 清空缓存池
    public function clear(): bool
    {
        return $this->redis()->clear();
    }


    ## Redis缓存句柄
    public function redis()
    {
        return CacheStatic::store('redis');
    }
    public function redisHandler()
    {
        return $this->redis()->handler();
    }
}
