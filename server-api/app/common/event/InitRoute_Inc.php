<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:19
 */

declare(strict_types=1);

namespace app\common\event;

use app\common\helper\StringHelper;
use think\facade\Event;
use think\Service;
use think\facade\Route;
use think\facade\Request;
use think\facade\Middleware;
use app\common\services\annotation\MethodService;

class InitRoute extends Service
{
    /**
     * 请求路径
     */
    protected string $pathinfo;

    /**
     * 当前应用
     */
    protected string $appName;

    protected string $appPath;

    protected string $modulePath;

    protected string $controller;

    protected string $action;

    protected mixed $route;

    protected string $routePath;

    protected string $class;
    private array $classAnnotation = [];

    public function __construct()
    {
        // 当前应用
        $this->appName = config('app.default_app');
        //默认控制器
        $this->controller = config('route.default_controller');

        $this->action = config('route.default_action');

        $this->pathinfo = request()->pathinfo();
    }

    public function handle()
    {
        $pathinfoArray = explode('/',  $this->pathinfo);

        !empty($pathinfoArray[0]) && $this->appName = $pathinfoArray[0];
        isset($pathinfoArray[1]) && $this->controller = $pathinfoArray[1];
        isset($pathinfoArray[2]) && $this->action = $pathinfoArray[2];
        $this->modulePath = "app/" . $this->appName;
        $this->setAppPath('app');

        if($this->appName == 'admin'){
            return view(app()->getRootPath() . 'public/admin/index.html');
        }elseif($this->appName == 'web'){
            return view(app()->getRootPath() . 'public/web/index.html');
        }

        $this->routePath = $this->appName .'/' . $this->controller . '/' . $this->action;

        app()->setNamespace(strtr($this->modulePath, '/', '\\'));

        request()->module($this->appName);

        if(empty($this->pathinfo)) Request::setPathinfo($this->routePath);

        $this->setRule();

        $this->getClass();

        $eventListenFile = app()->getRootPath() . $this->appPath .'/' .'event.php';
        if(file_exists($eventListenFile)){
            $eventListens = require_once $eventListenFile;
            Event::listenEvents($eventListens);
        }
        $this->checkMethod();
    }

    /**
     * 获取类
     */
    private function getClass(){

        if (str_contains($this->controller, '.')) {
            $arr = explode('.',$this->controller);
            $str1 =  '';
            foreach ($arr as $key => $item) {
                if(($key+1) < count($arr))
                    $str1 .= $item .'/';
                if($key+1 == count($arr))
                    $str1 .=  StringHelper::convertUnderline($item);
            }
            $classPath = "{$this->modulePath}/controller/". $str1;
        }else{
            $classPath = "{$this->modulePath}/controller/".StringHelper::convertUnderline($this->controller);
        }
        if(file_exists( root_path(). $classPath .'.php')){

            $this->class = strtr($classPath, '/', '\\');
            $this->getMethodAnnotation();
        }
        $this->setMiddleware();
    }

    /**
     * 设置应用目录
     */
    private function setAppPath($appPath = 'app'): void
    {
        $this->appPath = $appPath . DIRECTORY_SEPARATOR .$this->appName;
        app()->setAppPath($this->appPath . DIRECTORY_SEPARATOR . $this->controller . DIRECTORY_SEPARATOR);
    }

    /**
     * 设置路由
     */
    private function setRule(): void
    {
        $this->route = Route::rule($this->pathinfo, $this->routePath);
    }

    private function getMethodAnnotation(): void
    {
        $methodAnnotationSet = [];
        $methodAnnotation = new MethodService($this->class,$this->action);
        $methodAnnotation->matchAllMethodAnnotation(function (array $annotation, \ReflectionMethod $method) use (&$methodAnnotationSet) {
            if(!empty($annotation['action']) && $annotation['action'] == $this->action){
                $methodAnnotationSet = $annotation;
            }
        });
        $this->classAnnotation = $methodAnnotationSet;
    }

    /**
     * 设置中间件
     */
    private function setMiddleware(){
        $middleware = [\app\common\middleware\AllowOriginMiddleware::class];

        if(!empty($this->classAnnotation['middleware'])){
            $middleware = array_merge($middleware,$this->classAnnotation['middleware']);
        }
        if(file_exists(app()->getRootPath() . $this->appPath . '/middleware/middleware.php') ){
            $appDefaultMiddleware = include app()->getRootPath() . $this->appPath . '/middleware/middleware.php';
            $middleware = array_merge($middleware,$appDefaultMiddleware);
        }
        //过滤忽略的中间件
        if(!empty($this->classAnnotation['noMiddleware']) ){
            foreach ($middleware as $key => $item) {
                if(in_array( $item , $this->classAnnotation['noMiddleware'])){
                    unset($middleware[$key]);
                }
            }
        }
        request()->annotate($this->classAnnotation);
        Middleware::import($middleware);
    }

    private function checkMethod(): void
    {
        // 检测请求方式 过滤OPTIONS 跨域问题
        if((request()->method() !== 'OPTIONS' && !empty($this->classAnnotation['method']) && !in_array(request()->method(), $this->classAnnotation['method'] ))){
            die("非法请求！Not Allowed：".request()->method());
            exit();
        }
    }
}