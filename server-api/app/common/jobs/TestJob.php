<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2024/3/20 16:20
 */
declare(strict_types=1);
namespace app\common\jobs;

use think\facade\Log;
use think\queue\Job;
use think\Exception;

class TestJob
{
    public function fire(Job $job, $data)
    {
        try {
            echo "12332132123";
            $emailSent = true;
            if ($emailSent) {
                $job->delete();
            } else {
                if ($job->attempts() > 3) { // 如果尝试超过3次，则将任务记录为失败
                    $job->failed();
                } else {
                    $job->release(10); // 否则延迟10秒后重新尝试
                }
            }

        } catch (\Exception $e) {
            $job->failed($e);
        }
    }

    public function failed($data)
    {
        // 记录任务执行失败日志
        Log::record('jobs: ' . json_encode($data), 'error');
    }
}