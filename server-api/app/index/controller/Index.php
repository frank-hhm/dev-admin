<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:32
 */
declare(strict_types=1);
namespace app\index\controller;

use app\BaseController;
use app\common\enum\EnumFactory;
use app\common\services\common\CaptchaService;
use think\facade\Request;

class Index extends BaseController
{
    public function index()
    {
        $request = Request::instance();
        header('Location:' . $request->domain() . '/admin');
        exit();
    }

}