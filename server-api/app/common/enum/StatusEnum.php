<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 12:41
 */

declare(strict_types=1);

namespace app\common\enum;

use app\common\enum\BaseEnum;

/**
 * 枚举类
 * Class StatusEnum
 * @package app\common\enum
 */
class StatusEnum extends BaseEnum
{
    // 正常
    const NORMAL = 1;

    // 禁用
    const DISABLE = 0;

    /**
     * 获取枚举数据
     * @return array
     */
    public static function data()
    {
        return [
            self::NORMAL => [
                'name' => '正常',
                'value' => self::NORMAL,
            ],
            self::DISABLE => [
                'name' => '禁用',
                'value' => self::DISABLE,
            ],
        ];
    }
}
