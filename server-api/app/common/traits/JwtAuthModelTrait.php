<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:25
 */
declare(strict_types=1);

namespace app\common\traits;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use think\facade\Env;
use UnexpectedValueException;

trait JwtAuthModelTrait
{
    public function getToken(string $type, array $params = []): array
    {
        $id = $this->{$this->getPk()};
        $host = app()->request->host();
        $time = time();

        $params += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => strtotime('+30 days'),
        ];
        $params['jti'] = compact('id', 'type');
        $token = JWT::encode($params, Env::get('app.app_key', 'default'));

        return compact('token', 'params');
    }

    public static function parseToken(string $jwt): array
    {
        JWT::$leeway = 60;

        $data = JWT::decode($jwt, Env::get('app.app_key', 'default'), array('HS256'));

        $model = new self();
        return [$model->where($model->getPk(), $data->jti->id)->find(), $data->jti->type];
    }
}
