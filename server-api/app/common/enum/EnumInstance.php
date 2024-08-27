<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 12:41
 */

declare(strict_types=1);

namespace app\common\enum;
use think\Container;
/**
 * 基类
 * Class EnumInstance
 */
abstract class EnumInstance
{

    protected static $data;

    public static $class = [
        'status'=> '\\app\\common\\enum\\StatusEnum'
    ];
    public function __construct(){
    }

    /**
     * 静态实例对象
     * @param array $args
     * @return static
     */
    public static function instance(...$args)
    {
        $class = static::$class;
        if(!empty($args[0])){
            $source = explode('.',$args[0]);
            foreach ($source as $item) {
                $class = $class[$item];
            }
            static::$data = $class::data();
        }
        return Container::getInstance()->make(static::class, $args);
    }

    public static function getAll(){
        $classAll = static::getClass(static::$class);
        $data = [];
        foreach ($classAll as $key => $value) {
            $itemKey = str_replace("\\app\\common\\enum\\","",$value);
            $itemKey = str_replace("\\",".",$itemKey);
            $data[$itemKey] =  $value::data();
        }
        return $data;
    }

    public static function getClass($data){
        $classData = [];
        foreach ($data as $key => $value) {
            if(is_array($value)){
                $classData = array_merge(static::getClass($value),$classData);
            }else{
                $classData[] = $value;
            }
        }
        return $classData;
    }
}

