<?php
declare (strict_types = 1);

namespace app;

use app\common\constants\StatusCode;
use app\common\exception\CommonException;
use app\common\helper\StringHelper;
use app\common\services\common\NodeService;
use app\common\services\common\ValidateService;
use think\facade\App;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\facade\Config;
use think\facade\Lang;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     */
    protected mixed $request;

    /**
     * 应用实例
     * @var App
     */
    protected App $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected bool $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected array $middleware = [];

    public mixed $service;
    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = app("request");

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}


    /**
     * 快捷输入并验证（ 支持 规则 # 别名 ）
     * @param array $rules 验证规则（ 验证信息数组 ）
     * @param string $type 输入方式 ( post. 或 get. )
     * @param string $echoType
     * @return array
     */
    protected function vali(array $rules, string $type = '', string $echoType=''): array
    {
        return ValidateService::instance()->init($rules, $type,$echoType);
    }


    protected function checkAuth($roles = -1): void
    {
        $node = NodeService::instance()->getCurrent();
        $annotate = $this->request->annotate();
        $noAuth = $annotate['noAuth'][0] ?? false;
        $noAuth = !StringHelper::isTrue($noAuth);
        if($roles != -1 && $noAuth && $roles !== false &&  !in_array($node,$roles)){
            throw new CommonException('暂无api接口权限！',702);
        }
    }

    /**
     * 操作成功
     * @param string | array $message 提示消息
     * @param string|array $data 返回数据
     * @param string | int | array $code 返回码
     */
    protected function success(string|array $message = 'success', string | array $data = [], string|int|array $code = StatusCode::SUCCESS): void
    {
        if (is_array($message)) {
            $data = $message;
            $message = 'success';
        }
        $this->result($message, $data, $code);
    }

    /**
     * 操作失败
     * @param string $message 提示消息
     * @param string | array $data 数据
     * @param string | int | array $code 错误码
     */
    protected function error(string $message = 'error', string|array $data = [], string|int|array $code = StatusCode::ERROR): void
    {
        $this->result($message, $data, $code);
    }

    /**
     * 返回 API 数据
     * @param string $message 提示消息
     * @param string | array $data 返回数据
     * @param string | int | array $code 错误码
     */
    public function result(string $message, string|array $data = [], string|int|array $code = [])
    {
        if (is_array($code) && isset($code[0])) {
            $code = $code[0];
        }
        $result = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
        throw new HttpResponseException(json($result));
    }
}
