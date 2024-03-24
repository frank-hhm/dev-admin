<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2024/3/24 10:25
 */
declare(strict_types=1);
namespace app\swoole\websocket;

use think\response\Json;
use app\swoole\websocket\Response;

class Base
{
    public Response $response;

    public function __construct()
    {
        $this->response = app(Response::class);
    }

}