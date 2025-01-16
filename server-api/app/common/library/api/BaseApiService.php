<?php
/**
 * @Date: 2025/1/14 15:01
 */
declare(strict_types=1);
namespace app\common\library\api;

use GuzzleHttp\Client;

abstract class BaseApiService
{
    public mixed $client;

    protected mixed $response;
    // 错误信息
    protected mixed $error;

    protected int $timeout = 5;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function request($url,$method = "post",$options = []){
        try {
            $this->response = $this->client->$method($url,$options);
            return $this->getResult();
        }catch (\Exception $e){
            $this->error = $e->getMessage();
            return false;
        }
    }
    public function get($url,$options = []){
        try {
            $options['timeout'] = $this->timeout;
            $this->response = $this->client->get($url,$options);
            return $this->getResult();
        }catch (\Exception $e){
            $this->error = $e->getMessage();
            return false;
        }
    }
    public function post($url,$options = []){
        try {
            $options['timeout'] = $this->timeout;
            $this->response = $this->client->post($url,$options);
            return $this->getResult();
        }catch (\Exception $e){
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getResult(){
        return json_decode($this->response->getBody()->getContents(),true);
    }
    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError(): mixed
    {
        return $this->error;
    }

    /**
     * 是否存在错误
     * @return bool
     */
    public function hasError(): bool
    {
        return !empty($this->error);
    }

    /**
     * 设置错误信息
     * @param $error
     */
    public function setError($error): void
    {
        empty($this->error) && $this->error = $error;
    }
}