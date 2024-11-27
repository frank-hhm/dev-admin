<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:20
 */

declare(strict_types=1);

namespace app\common\helper;

/**
 * 操作数组帮助类
 * Class ArrayHelper
 * @package app\common\helper
 */
class ArrayHelper
{
    /**
     * 检查状态集
     */
    public static function checkArr($res): bool
    {
        foreach ($res as $v) {
            if (!$v) {
                return false;
            }
        }
        return true;
    }
    /**
     * 对数组增加默认值
     */
    public static function getDefaultValue(array $keys, array $configList = []): array
    {
        $value = [];
        foreach ($keys as $val) {
            if (is_array($val)) {
                $k = $val[0] ?? '';
                $v = $val[1] ?? '';
            } else {
                $k = $val;
                $v = '';
            }
            $value[$k] = $configList[$k] ?? $v;
        }
        return $value;
    }

    /**
     * 获取二维数组中最大的值
     */
    public static function getArrayMax($arr, $field): int|string
    {
        $temp = [];
        $maxk = 0;
        foreach ($arr as $k => $v) {
            $temp[] = $v[$field];
        }
        if (!count($temp)) return $maxk;
        $maxNumber = max($temp);
        foreach ($arr as $k => $v) {
            if ($maxNumber == $v[$field]){
                $maxk = $k;
            }
        }
        return $maxk;
    }

    /**
     * 判断数组是否有存在大于
     */
    public static function isNumberMaxInArray($arr, $number): bool
    {
        $maxk = false;
        foreach ($arr as $k => $v) {
            if($v >= $number){
                $maxk = true;
            }
        }
        return $maxk;
    }


    /**
     * 获取二维数组中最小的值
     */
    public static function getArrayMin($arr, $field): int|string
    {
        $temp = [];
        $mink = 0;
        foreach ($arr as $k => $v) {
            $temp[] = $v[$field];
        }
        if (!count($temp)) return $mink;
        $minNumber = min($temp);
        foreach ($arr as $k => $v) {
            if ($minNumber == $v[$field]){
                $mink = $k;
                continue;
            }
        }
        return $mink;
    }

    //二维数组根据字段获取最小数组
    public static function getArrayMinItem($arr,$field)
    {
        if(!is_array($arr) || !$field){
            return false;
        }
        $temp = array();
        foreach ($arr as $key=>$val) {
            $temp[] = $val[$field];
        }
        $newArr = [];
        foreach ($arr as $key => $value) {
            if($value[$field] === min($temp) ){
                $newArr = $value;
            }
        }
        return $newArr;
    }

    /**
     * 数组转字符串去重复
     */
    public static function unique(array $data): array
    {
        return array_unique(explode(',', implode(',', $data)));
    }

    /**
     * 获取数组中去重复过后的指定key值
     */
    public static function getUniqueKey(array $list, string $key): array
    {
        return array_unique(array_column($list, $key));
    }

    /**
     * 获取数组钟随机值
     */
    public static function getArrayRandKey(array $data)
    {
        if (!$data) {
            return false;
        }
        $mun = rand(0, count($data));
        if (!isset($data[$mun])) {
            return self::getArrayRandKey($data);
        }
        return $data[$mun];
    }

    /**
     * 获取数组级联
     */
    public static function getArrayTreeValue(array $data, $value,$_key = 'value'): array
    {
        static $childrenValue = [];
        $arrtree = [];
        foreach ($data as &$item) {
            if ($item[$_key] == $value) {
                $childrenValue[] = $item[$_key];
                if ($item['pid']) {
                    $value = $item['pid'];
                    unset($item);
                    $arrtree = self::getArrayTreeValue($data, $value,$_key);
                    continue;
                }
            }
        }
        if($arrtree){
            return $arrtree;
        }
        return $childrenValue;
    }

    /**
     * 获取级联数组子级
     */
    public static function getArrayTreeChildren(array $data, string $childrenname = 'children', string $keyName = 'id', string $pidName = 'pid'): array
    {
        $list = array();
        foreach ($data as $value) {
            $list[$value[$keyName]] = $value;
        }
        static $tree = array(); #格式化好的树
        foreach ($list as $item) {
            if (isset($list[$item[$pidName]])) {
                $list[$item[$pidName]][$childrenname][] = &$list[$item[$keyName]];
            } else {
                $tree[] = &$list[$item[$keyName]];
            }
        }
        return $tree;
    }

    /**
     * 数组分级排序
     */
    public static function sortListTier($data, $pid = 0, $field = 'pid', $pk = 'id', $html = '|-----', $level = 1, $clear = true): array
    {
        static $list = [];
        if ($clear) $list = [];
        foreach ($data as $k => $res) {
            if ($res[$field] == $pid) {
                $res['html'] = str_repeat($html, $level - 1);
                $list[] = $res;
                unset($data[$k]);
                self::sortListTier($data, $res[$pk], $field, $pk, $html, $level + 1, false);
            }
        }
        return $list;
    }

    /**
     * 一维数组生成数据树
     */
    public static function arr2tree($list, $cid = 'id', $pid = 'pid', $sub = 'sub'): array
    {
        list($tree, $tmp) = [[], array_combine(array_column($list, $cid), array_values($list))];
        foreach ($list as $vo) isset($vo[$pid]) && isset($tmp[$vo[$pid]]) ? $tmp[$vo[$pid]][$sub][] = &$tmp[$vo[$cid]] : $tree[] = &$tmp[$vo[$cid]];
        unset($tmp, $list);
        return $tree;
    }


    /**
     * url字符转数组中
     */
    public static function urlParamsToArray($string): array
    {
        if(empty($string)) return [];
        $param = explode('&', $string);
        $paramArr = [];
        foreach ($param as $k => $v) {
            $paramItem = explode('=', $v);
            if(!empty($paramItem[0])){
                $paramArr[$paramItem[0]] = $paramItem[1] ?? '';
            }
        }
        return $paramArr;
    }

    /**
     * 获取数组中指定的列
     */
    public static function getArrayColumn($source, $column): array
    {
        $columnArr = [];
        foreach ($source as $item) {
            $columnArr[] = $item[$column];
        }
        return $columnArr;
    }

    /**
     * 获取数组中指定的列
     * @param $source
     * @param $column
     * @return array
     */
    public static function getArray2Column($source, $column): array
    {
        $columnArr = [];
        foreach ($source as $item) {
            foreach ($item[$column] as $v) {
                $columnArr[] = $v;
            }
        }
        return $columnArr;
    }

    /**
     * 获取合并二维数组
     * @param $source
     * @return array
     */
    public static function getArray2($source): array
    {
        $columnArr = [];
        foreach ($source as $item) {
            foreach ($item as $v) {
                $columnArr[] = $v;
            }
        }
        return $columnArr;
    }


    /**
     * 对象转数组
     */
    public static function objectToArray($data) {
        if(is_object($data)) {
            $data = (array)$data;
        }
        if(is_array($data)) {
            foreach($data as $key=>$value) {
                $data[$key] = static::objectToArray($value);
            }
        }
        if($data === null) $data = [];
        return $data;
    }
    /*
    json数据转数组
     */
    public static function jsonToArray($data = ''){
        if(!empty($data)){
            $data = json_decode($data);
            if(is_object($data)) $data = static::objectToArray($data);
        }
        return $data;
    }

    /**
     * 数组元素转int
     */
    public static function arrayItemToInt($arr){
        foreach ($arr as &$item) {
            is_string($item) && $item = (int)$item;
        }
        return $arr;
    }

    /**
     * 把二维数组中某列设置为key返回
     */
    public static function arrayColumn2Key($source, $index): array
    {
        $data = [];
        foreach ($source as $item) {
            $data[$item[$index]] = $item;
        }
        return $data;
    }

    /**
     * 把二维数组中指定值的item返回
     */
    public static function getArrayItemByColumn($array, $column, $value)
    {
        $columnItem = [];
        foreach ($array as $item) {
            if ($item[$column] == $value) {
                $columnItem = $item;
                continue;
            }
        }
        return $columnItem;
    }

    /**
     * 把二维数组中指定值的item value返回
     */
    public static function getArrayItemValueByColumn($array, $column, $value, $key)
    {
        $columnItmeValue = '';
        foreach ($array as $item) {
            if ($item[$column] == $value) {
                $columnItmeValue = $item[$key] ?? '';
                continue;
            }
        }
        return $columnItmeValue;
    }

    /**
     * 把二维数组中指定值的item返回
     */
    public static function getArray2ByColumn($array, $column, $value): array
    {
        $columnArr = [];
        foreach ($array as $item) {
            if ($item[$column] == $value) {
                $columnArr[] = $item;
            }
        }
        return $columnArr;
    }

    public static function getArrayColumnSum($array, $column): float|int
    {
        $sum = 0;
        foreach ($array as $item) {
            $sum += $item[$column] * 100;
        }
        return $sum / 100;
    }

    public static function attribute(&$source, $defaultData, $isArray = false)
    {
        if (!$isArray) $dataSource = [&$source]; else $dataSource = &$source;
        foreach ($dataSource as &$item) {
            foreach ($defaultData as $key => $value) {
                $item[$key] = $value;
            }
        }
        return $source;
    }
    public static function xmlToArr($xml)
    {
        $res = @simplexml_load_string ( $xml,NULL, LIBXML_NOCDATA );
        $res = json_decode ( json_encode ( $res), true );
        return $res;
    }

}
