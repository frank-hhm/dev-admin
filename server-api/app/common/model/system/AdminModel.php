<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:23
 */

declare(strict_types=1);

namespace app\common\model\system;

use app\common\enum\EnumFactory;
use app\common\helper\ArrayHelper;
use app\common\helper\IpHelper;
use  app\common\model\BaseModel;
use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use think\model\concern\SoftDelete;

/**
 * 管理员模型
 * Class AdminModel
 * @package app\common\model\system
 */
class AdminModel extends BaseModel
{
    use ModelTrait;
    use JwtAuthModelTrait;
    use SoftDelete;

    ## 数据表主键
    protected $pk = 'id';

    ## 模型名称
    protected $name = 'system_admin';
    protected string $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    /**
     * 权限修改器
     */
    public function setRolesAttr($value)
    {
        return !empty($value)?implode(',', $value):[];
    }

    /**
     * 时间获取器
     */
    public function getLastTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    /**
     * 权限获取器
     */
    public function getRolesAttr($value)
    {
        return ArrayHelper::arrayItemToInt(!empty($value)?explode(',', $value):[]);
    }

    /**
     * 状态获取器
     */
    public function getStatusAttr($value)
    {
        return EnumFactory::instance('status')->getItem($value);
    }

    public function getLastIpAttr($value,$data){
        if(!empty($value)){
            return [
                'text' => IpHelper::getIPCountry($value),
                'value' => $value
            ];
        }
        return [
            'text' => '',
            'value' => $value
        ];
    }
}
