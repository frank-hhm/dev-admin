<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 12:37
 */

declare(strict_types=1);

namespace app\common\dao;

use app\common\model\MediaModel;
/**
 * 素材
 * Class MediaDao
 * @package app\common\dao
 */
class MediaDao extends BaseDao
{
    /**
     * 设置模型
     */
    protected function setModel(): string
    {
        return MediaModel::class;
    }
}