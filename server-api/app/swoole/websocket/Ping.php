<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2024/3/22 14:56
 */
declare(strict_types=1);
namespace app\swoole\websocket;


use Psr\SimpleCache\InvalidArgumentException;
use think\facade\Log;
use think\swoole\App;

/**
 * Class Ping
 * @package app\swoole\websocket
 */
class Ping
{
    /**
     */
    protected mixed $redis;


    const CACHE_PINK_KEY = 'ws.p.';


    const CACHE_SET_KEY = 'ws.s';

    /**
     * Ping constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        try {
            $this->redis = $app->cache->store('redis');
            $this->destroy();
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $time
     * @param int $timeout
     * @throws InvalidArgumentException
     */
    public function createPing($id, $time, int $timeout = 0): void
    {
        $this->updateTime($id, $time, $timeout);
        $this->redis->sAdd(self::CACHE_SET_KEY, $id);
    }

    /**
     * @param $id
     * @param $time
     * @param int $timeout
     * @throws InvalidArgumentException
     */
    public function updateTime($id, $time, int $timeout = 0): void
    {
        $this->redis->set(self::CACHE_PINK_KEY . $id, $time, $timeout);
    }

    /**
     * @param $id
     */
    public function removePing($id): void
    {
        $this->redis->del(self::CACHE_PINK_KEY . $id);
        $this->redis->del(self::CACHE_SET_KEY, $id);
    }

    /**
     * @param $id
     * @return bool|string|null
     */
    public function getLastTime($id): bool|string|null
    {
        try {
            return $this->redis->get(self::CACHE_PINK_KEY . $id);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return null;
        }

    }

    /**
     */
    public function destroy(): void
    {
        $members = $this->redis->sMembers(self::CACHE_SET_KEY) ?: [];
        foreach ($members as $k => $member) {
            $members[$k] = self::CACHE_PINK_KEY . $member;
        }
        if (count($members))
            $this->redis->sRem(self::CACHE_SET_KEY, ...$members);
    }
}