<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2024/3/22 14:50
 */
declare(strict_types=1);
namespace app\swoole\websocket;

use app\common\services\common\CacheService;
use Psr\SimpleCache\InvalidArgumentException;
use Swoole\Websocket\Frame;
use think\App;
use think\Event;
use think\Config;
use think\Request;
use think\response\Json;
use think\swoole\Websocket;
use think\swoole\websocket\Room;
use think\swoole\contract\websocket\HandlerInterface;
use app\swoole\websocket\Room as NowRoom;

/**
 * Class Manager
 * @package app\webscoket
 */
class Manager extends Websocket implements HandlerInterface
{
    /**
     */
    protected mixed $server;

    /**
     */
    protected mixed $config;

    /**
     * 定时器执行间隔(毫秒)
     */
    protected int $interval = 2000;

    /**
     */
    protected mixed $pingService;

    /**
     */
    protected int $cache_timeout;

    /**
     */
    protected mixed $response;

    /**
     */
    protected mixed $cache;

    const MODULES = ['member','sys'];

    protected string $module;

    protected mixed $nowRoom;
    /**
     * Manager constructor.
     */
    public function __construct(App $app, Websocket $server, Room $room,NowRoom $nowRoom,  Event $event, Response $response, Ping $ping)
    {
        parent::__construct($app, $room, $event);
        $this->response = $response;
        $this->pingService = $ping;
        $this->server = $server;
        $this->nowRoom = $nowRoom;
        $this->cache = app(CacheService::class)->redisHandler();
        $this->nowRoom->setCache($this->cache);
        $this->cache_timeout = intval(app()->config->get('swoole.websocket.ping_timeout', 60000) / 1000) + 2;
    }

    /**
     */
    public function onOpen(Request $request)
    {
        $fd = $this->server->getSender();
        try {
            $module = $request->get('module');
            $token = $request->get('token');
            if (!$token || !in_array($module, self::MODULES)) {
                return $this->close();
            }
            $this->module = $module;
            $this->nowRoom->module($this->module);
            $res = $this->exec(
                [
                    'action'=>'login',
                    'fb'=>$fd,
                    'form_type'=>$request->get('form_type', null),
                    'data'=>[
                        'token'=>$token
                    ]
                ]
            )->getData();
            if ($res['code'] != 1) {
                return $this->close();
            }
            $uid = $res['data']['uid'] ?? 0;
            if ($uid) {
                $this->login($this->module, $uid, $fd);
            }
            $this->nowRoom->add($fd, $uid);
            $this->pingService->createPing($fd, time(), $this->cache_timeout);
            $this->send($fd, $this->response->message('ping'));
            return $this->send($fd, $this->response->success($res['data']));
        }catch (\Exception $e){
            dump($e);
            $this->send($fd, $this->response->error($e->getMessage()));
        }
    }

    public function login($module, $uid, $fd): void
    {
        $key = '_ws_' . $module;
        $this->cache->sadd($key, $fd);
        $this->cache->sadd($key . $uid, $fd);
        $this->refresh($module, $uid);
    }
    /**
     */
    public function onMessage(Frame $frame)
    {
        echo date("Y-m-d H:i:s")." onMessage".PHP_EOL;
        try {
            $fd = $this->server->getSender();
            $result = json_decode($frame->data, true) ?: [];
            if (!isset($result['action']) || !$result['action']) return true;
            if ($result['action'] == 'ping') {
                return $this->send($fd, $this->response->message('ping'));
            }
            $res = $this->exec(
                array_merge($result,
                [
                    'fb'=>$fd,
                ])
            )->getData();
            if ($res) return $this->send($fd, $res);
            return true;
        }catch (\Exception $e){
            dump($e);
        }
    }

    public function onClose(){
        echo date("Y-m-d H:i:s")." onClose".PHP_EOL;
    }


    public function encodeMessage($message){
        return $message;
    }


    /**
     * 发送文本响应
     * @param $fd
     * @param $json
     * @return void|null
     * @throws InvalidArgumentException
     */
    public function send($fd, $json)
    {
        $this->pingService->createPing($fd, time(), $this->cache_timeout);
        try{
            $data = $json->getData();
            $data = is_array($data) ? json_encode($data) : $data;
            $this->server->push($data);
        } catch (\Throwable $e) {
            $this->server->close();
        }
    }

    /**
     * 刷新key
     * @param $type
     * @param $uid
     */
    public function refresh($type, $uid)
    {
        $key = '_ws_' . $type;
        $this->cache->expire($key, 1800);
        $this->cache->expire($key . $uid, 1800);
    }
    /**
     * 执行调度
     */
    protected function exec($result = [],$class = 'Index')
    {
        if (!is_array($result)) {
            return null;
        }
        $class = '\\app\\swoole\\websocket\\'.$this->module .'\\'.ucfirst($class);

        $method = $result['action'] ?? 'index';
        $args = $result['data'] ?? [];
        // 检查类和方法是否存在
        if (!class_exists($class) || !method_exists($class, $method)) {
            $errorMessage = "Class '$class' or method '$method' does not exist.";
            $this->send($result['fb'] ?? '', $this->response->error($errorMessage));
            return false;
        }
        try {
            $instance = new $class();
            $res =  $instance->{$method}($args);
            return $res;
        } catch (\Throwable $e) {
           return $this->response->error($e->getMessage());
        }
    }

    public function close(): bool
    {
         $this->server->close();
         return true;
    }
}