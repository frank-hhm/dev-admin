<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 12:40
 */

declare(strict_types=1);

namespace app\common\enum;

use app\common\enum\EnumInstance;
/**
 * 枚举扩展工厂类
 * Class EnumFactory
 * @package app\enum
 */
class EnumFactory  extends EnumInstance
{
    ##获取数据
    public static function data()
    {
        return self::$data;
    }

    ##获取名称
    public static function getName()
    {
        static $names = [];
        if (empty($names)) {
            foreach (self::$data as $item)
                $names[$item['value']] = $item['name'];
        }
        return $names;
    }

    ## 获取单项
    public static function getItem($key = '',$name = '')
    {
        $data = self::$data;
        if (isset($data[$key]) && !empty($data[$key])){
            $item = $data[$key];
            if(!empty($name)){
                $item['name'] = vsprintf($item['name'], $name);
            }
            return $item;
        }
        return [
            'value'=>$key,
            'name'=>''
        ];
    }

    ## 获取单项名称
    public static function getItemName($key = '')
    {
        static $itemName = '';
        $data = self::$data;
        if (isset($data[$key])) {
            $itemName = $data[$key]['name'];
        }
        return $itemName;
    }

    ##获取标识集
    public static function getAttr()
    {
        static $attrArr = [];
        $data = self::$data;
        foreach ($data as $key => $item) {
            $attrArr[] = $item['value'];
        }
        return implode(',',$attrArr);
    }
}