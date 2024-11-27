<?php
/**
 * @Date: 2024/11/27 15:55
 */
declare(strict_types=1);
namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\LoginLogsModel;
/**
 * 日记
 * Class LoginLogsDao
 * @package app\common\dao
 */
class LoginLogsDao extends BaseDao
{
    /**
     * 设置模型
     */
    protected function setModel(): string
    {
        return LoginLogsModel::class;
    }
}
