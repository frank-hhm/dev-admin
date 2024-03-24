<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2024/3/24 10:47
 */
declare(strict_types=1);
namespace app\swoole\websocket;

use think\swoole\Table;

/**
 * 房间管理
 * Class Room
 * @package app\webscoket
 */
class Room
{

    /**
     * 类型
     */
    protected string $module = '';

    /**
     * fd前缀
     * @var string
     */
    protected string $tableFdPrefix = 'ws_fd_';

    /**
     *
     * @var array
     */
    protected array $room = [];

    protected mixed $cache;

    /**
     *
     */
    const USER_INFO_FD_PRE = 'socket_user_list';

    const TYPE_NAME = 'socket_user_type';

    /**
     * 设置缓存
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * 设置表
     */
    public function module(string $module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * 获取表实例
     */
    public function getTable(string $tableName = 'sys')
    {
        return app()->make(Table::class)->get($tableName);
    }

    /**
     * 添加fd
     */
    public function add(string $key, int $userId = 0, int $toUserId = 0, int $tourist = 0)
    {
        $nowkey = $this->tableFdPrefix . $key;
        $data = [
            'fd' => $key,
            'is_open' => 1,
            'module' => $this->module ?: 'sys',
            'user_id' => $userId,
            'to_user_id' => $toUserId,
            'tourist' => $tourist
        ];
        return $this->getTable()->set($nowkey, $data);
    }

    /**
     * 修改数据
     */
    public function update(string $key, $field = null, $value = null)
    {
        $nowkey = $this->tableFdPrefix . $key;
        $res = true;
        if (is_array($field)) {
            $this->getTable()->del($nowkey);
            $res = $this->getTable()->set($nowkey, $field);
        } else if (!is_array($field) && $value !== null) {
            $data = $this->getTable()->get($nowkey);
            if (!$data) {
                return false;
            }
            $data[$field] = $value;
            $this->getTable()->del($nowkey);
            $res = $this->getTable()->set($nowkey, $data);
        }
        return $res;
    }

    /**
     * 重置
     */
    public function reset()
    {
        $this->module = '';
        return $this;
    }

    /**
     * 删除
     */
    public function del(string $key)
    {
        return $this->getTable()->del($this->tableFdPrefix . $key);
    }

    /**
     * 是否存在
     */
    public function exist(string $key)
    {
        return $this->getTable()->exist($this->tableFdPrefix . $key);
    }

    /**
     * 获取fd的所有信息
     */
    public function get(string $key, string $field = null)
    {
        return $this->getTable()->get($this->tableFdPrefix . $key, $field);
    }
}
