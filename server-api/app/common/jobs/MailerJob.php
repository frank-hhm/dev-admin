<?php
/**
 * @Date: 2025/1/15 13:46
 */
declare(strict_types=1);

namespace app\common\jobs;

use think\facade\Log;
use think\queue\Job;
use think\Exception;

/**
 * 邮箱发送任务
 */
class MailerJob
{

    /**
     * fire是消息队列默认调用的方法
     */
    public function fire(Job $job, $data)
    {
        if ($job->attempts() > 3) {
            $job->delete();
            return true;
        }

        if ($this->doJob($data)) {
            $job->delete();
        }
        return true;
    }

    public function doJob($data)
    {
        


    }
}