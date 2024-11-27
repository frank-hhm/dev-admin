<?php
/**
 * @Date: 2024/11/27 15:55
 */
declare(strict_types=1);
namespace app\common\model\system;

use app\common\enum\EnumFactory;
use app\common\model\BaseModel;
use app\common\traits\ModelTrait;

/**
 * 登录日记
 * Class LoginLogsModel
 * @package app\common\model
 */
class LoginLogsModel extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     */
    protected $pk = 'id';

    /**
     * 模型名称
     */
    protected $name = 'system_login_logs';

}