<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:24
 */
declare(strict_types=1);

namespace app\common\traits;

use think\Model;

/**
 * Trait ModelTrait
 * @package app\common\traits;
 */
trait ModelTrait
{

    /**
     * 时间段搜索器
     */
    public function searchTimeAttr($query, $value, $data)
    {
        if ($value) {
            $timeKey = $data['timeKey'] ?? 'create_time';
            if (is_array($value)) {
                $startTime = $value[0] ?? 0;
                $endTime = $value[1] ?? 0;
                if ($startTime || $endTime) {
                    $query->whereTime($timeKey, 'between',[$startTime, $endTime]);
                }
            } elseif (is_string($value)) {
                switch ($value) {
                    case 'today':
                    case 'week':
                    case 'month':
                    case 'year':
                    case 'yesterday':
                    case 'last year':
                    case 'last week':
                    case 'last month':
                        $query->whereTime($timeKey, $value);
                        break;
                    case 'quarter':
                        [$startTime, $endTime] = $this->getMonth();
                        $query->whereTime($timeKey, 'between',[strtotime($startTime), strtotime($endTime)]);
                        break;
                    case 'lately7':
                        $query->whereTime($timeKey, 'between', [strtotime("-7 day"), time()]);
                        break;
                    case 'lately30':
                        $query->whereTime($timeKey,'between', [strtotime("-30 day"), time()]);
                        break;
                    default:
                        if (str_contains($value, '-')) {
                            [$startTime, $endTime] = explode('-', $value);
                            $startTime = trim($startTime) ? strtotime($startTime) : 0;
                            $endTime = trim($endTime) ? strtotime($endTime) : 0;
                            if ($startTime && $endTime) {
                                $query->where($timeKey, 'between',[$startTime, $endTime]);
                            } else if (!$startTime && $endTime) {
                                $query->whereTime($timeKey, '<', $endTime + 86400);
                            } else if ($startTime && !$endTime) {
                                $query->whereTime($timeKey, '>=', $startTime);
                            }
                        }
                        break;
                }
            }
        }
    }

    /**
     * 获取本季度 time
     */
    public function getMonth(int $ceil = 0)
    {
        if ($ceil != 0) {
            $season = ceil(date('n') / 3) - $ceil;
        } else {
            $season = ceil(date('n') / 3);
        }
        $firstday = date('Y-m-01', mktime(0, 0, 0, ($season - 1) * 3 + 1, 1, (int)date('Y')));
        $lastday = date('Y-m-t', mktime(0, 0, 0, $season * 3, 1, (int)date('Y')));
        return [$firstday, $lastday];
    }
}
