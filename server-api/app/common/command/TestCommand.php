<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2024/3/20 16:17
 */
declare(strict_types=1);

namespace app\common\command;

use app\common\jobs\TestJob;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Log;

class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('TestCommand');
    }

    protected function execute(Input $input, Output $output)
    {

        echo "test";exit;
    }
}