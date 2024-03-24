<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2024/3/22 16:10
 */
declare(strict_types=1);
namespace app\swoole\websocket;

use app\common\constants\StatusCode;
use think\exception\HttpResponseException;
use think\response\Json;

/**
 * socket Response
 * Class Response
 * @package app\webscoket
 * @mixin Json
 */
class Response
{

    /**
     *
     * @var Json
     */
    protected Json $response;

    /**
     * Response constructor.
     */
    public function __construct(Json $response)
    {
        $this->response = $response;
    }

    /**
     * 操作成功
     * @param string | array $message 提示消息
     * @param string|array $data 返回数据
     * @param string | int | array $code 返回码
     */
    public function success(string|array $message = 'success', string | array $data = [], string|int|array $code = StatusCode::SUCCESS,string $action='message')
    {
        if (is_array($message)) {
            $data = $message;
            $message = 'success';
        }
        return $this->send($message, $data, $code, $action);
    }

    /**
     * 操作失败
     * @param string $message 提示消息
     * @param string | array $data 数据
     * @param string | int | array $code 错误码
     */
    public function error(string $message = 'error', string|array $data = [], string|int|array $code = StatusCode::ERROR,string $action='message')
    {
        return $this->send($message, $data, $code, $action);
    }

    /**
     * 返回 API 数据
     * @param string $message 提示消息
     * @param string | array $data 返回数据
     * @param string | int | array $code 错误码
     */
    public function send(string $message = '', string|array $data = [], string|int|array $code = [],string $action='message')
    {
        if (is_array($code) && isset($code[0])) {
            $code = $code[0];
        }
        $time =  time();
        $result = [
            'action'=>$action,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'time' => $time,
        ];
        $this->response->data($result);
        return $this->response;
    }

    /**
     * 设置返只有类型没有状态的返回数据
     * @param string $action
     * @param array|null $data
     * @return Json
     */
    public function message(string $action,?array $data = []): Json
    {
        $time =  time();
        $this->response->data(compact('action', 'data','time'));
        return $this->response;
    }


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->response, $name], $arguments);
    }
}
