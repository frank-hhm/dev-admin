<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:25
 */
declare(strict_types=1);

namespace app\common\services;

use think\App;
use think\facade\Config;
use think\facade\Db;
use think\Container;

abstract class BaseService
{
    /**
     * 应用实例
     * @var App
     */
    protected App $app;


    // 错误信息
    protected mixed $error;

    public mixed $dao;
    /**
     * Service constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 静态实例对象
     * @param array $args
     * @return static
     */
    public static function instance(...$args)
    {
        return Container::getInstance()->make(static::class, $args);
    }
    /**
     * 魔术函数
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->dao, $name)) {
            return call_user_func_array([$this->dao, $name], $arguments);
        }
        throw new \BadMethodCallException('Call to undefined method ' . get_class($this->dao) . '::' . $name . '()');
    }


    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError(): mixed
    {
        return $this->error;
    }

    /**
     * 是否存在错误
     * @return bool
     */
    public function hasError(): bool
    {
        return !empty($this->error);
    }

    /**
     * 设置错误信息
     * @param $error
     */
    public function setError($error): void
    {
        empty($this->error) && $this->error = $error;
    }
}