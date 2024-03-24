<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:19
 */

declare(strict_types=1);


namespace app\common\event;

use think\facade\Config;

/**
 * 初始化配置信息
 * @author InitConfig
 *
 */
class InitConfig
{
    public function handle(): void
    {
        ## 初始化常量
        $this->initConst();
    }

    /**
     * 初始化常量
     */
    private function initConst()
    {
        if (!defined('ROOT_PATH')) {
            //默认
            define('APIMODULE', 'api');

            define('SYSMODULE', 'sys');

            define('DEFAULTMODULE',  config('app.default_app'));

            //加载基础化配置信息
            define('__ROOT__',  env('COMMON.BASEURL', '127.0.0.1'));

            define('__UPLOAD__', 'uploads');

            //伪静态模式是否开启
            define('REWRITE_MODULE', true);

            ## public目录绝对路径
            define('PUBLIC_PATH', dirname(__FILE__, 3) . '/public/');
            ## 项目绝对路径
            define('ROOT_PATH', dirname(__FILE__, 3));

            //兼容模式访问
            if (!REWRITE_MODULE) {
                define('ROOT_URL', request()->root(true) . '/?s=');
            } else {
                define('ROOT_URL', request()->root(true));
            }

            //检测网址访问
            $url = request()->url(true);
            $url = strtolower($url);
            if (strstr($url, 'call_user_func_array') || strstr($url, 'invokefunction') || strstr($url, 'think\view')) {
                die("非法请求");
            }

            ## 应用模块
            $GLOBALS['app_array'] = ['index',SYSMODULE, APIMODULE];
        }
    }
}
