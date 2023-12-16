<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:29
 */
declare(strict_types=1);

namespace app\common\services\common;

use app\common\exception\CommonException;
use app\common\services\common\CacheService;
use app\common\services\BaseService;
use app\common\constants\StatusCode;
use Firebase\JWT\JWT;
use think\facade\Env;

/**
 * Jwt
 * Class JwtAuthService
 * @package library\services
 */
class JwtAuthService extends BaseService
{

    // token
    protected string $token;

    // 获取token
    public function getToken(int $id, string $type, array $params = []): array
    {
        $host = app()->request->host();
        $time = time();
        $exp_time = strtotime('+ 30day');
        $params += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => $exp_time,
        ];
        $params['jti'] = compact('id', 'type');
        $token = JWT::encode($params, Env::get('app.app_key', 'default'));

        return compact('token', 'params');
    }

    // 解析token
    public function parseToken(string $jwt): array
    {
        $this->token = $jwt;
        list($headb64, $bodyb64, $cryptob64) = explode('.', $this->token);
        $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));
        return [$payload->jti->id, $payload->jti->type];
    }

    // 验证token
    public function verifyToken(): void
    {
        JWT::$leeway = 60;
        JWT::decode($this->token, Env::get('app.app_key', 'default'), array('HS256'));
        $this->token = '';
    }

    // 获取token并放入令牌桶
    public function createToken(int $id, string $type, array $params = []): array
    {
        $tokenInfo = $this->getToken($id, $type, $params);
        $exp = $tokenInfo['params']['exp'] - $tokenInfo['params']['iat'] + 60;
        $res = CacheService::instance()->set(Env::get('redis.jwt').md5($tokenInfo['token']), ['uid' => $id, 'type' => $type, 'token' => $tokenInfo['token'], 'exp' => $exp], (int)$exp, $type);
        if (!$res) {
            throw new CommonException(StatusCode::ERROR);
        }
        return $tokenInfo;
    }
}

