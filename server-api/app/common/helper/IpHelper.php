<?php
/**
 * @Date: 2024/8/18 16:23
 */
declare(strict_types=1);

namespace app\common\helper;

use itbdw\Ip\IpLocation;

/**
 * 操作Ip帮助类
 * Class IpHelper
 * @package app\common\helper
 */
class IpHelper
{

    public static function validateIPv4($ip): bool|int
    {
        $pattern = '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';
        return preg_match($pattern, $ip);
    }

    public static function validateIPv6($ip): bool
    {
        // IPv6地址的基本正则表达式
        // 匹配冒号分隔的每段为1-4个十六进制字符，允许在地址中包含一次连续的两个冒号(::)表示的缩写形式
        $regex = '/^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/';

        // 使用preg_match进行匹配
        return (bool) preg_match($regex, $ip);
    }

    public static function getIPCountry($ip) {
        if(self::validateIPv6($ip)){
            $res = IpLocation::getLocation($ip);
            if(!empty($res["country"])){
                return $res["country"];
            }
            return "未知【IPv6】";
        }else if(self::validateIPv4($ip)){
            $ip2region = new \Ip2Region();
            $geo = $ip2region->memorySearch($ip);
            $arr = explode('|', str_replace(['0|'], '|', isset($geo['region']) ? $geo['region'] : ''));
            if($arr[0] == "中国" && isset($arr[2])){
                $ipStr = $arr[0].$arr[2];
                if(isset($arr[3])){
                    $ipStr .= $arr[3];
                }
                if(isset($arr[4])){
                    $ipStr = "【{$arr[4]}】".$ipStr;
                }
                return $ipStr;
            }
            return $arr[0] ?? "";
        }else{
            return "";
        }
    }

}