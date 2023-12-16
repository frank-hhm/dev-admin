<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 12:38
 */

declare(strict_types=1);

namespace app\common\dao;

use app\common\model\MediaGroupModel;
/**
 * 素材分组
 * Class MediaGroupDao
 * @package app\common\dao\mate
 */
class MediaGroupDao extends BaseDao
{
    /**
     * 设置模型
     */
    protected function setModel(): string
    {
        return MediaGroupModel::class;
    }
}