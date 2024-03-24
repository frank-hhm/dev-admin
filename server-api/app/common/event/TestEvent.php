<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2024/3/22 18:25
 */
declare(strict_types=1);


namespace app\common\event;

use think\facade\Config;

/**
 * 测试
 * @author TestEvent
 *
 */
class TestEvent
{
    public function handle(): void
    {
        dump('TestEvent');
    }
}