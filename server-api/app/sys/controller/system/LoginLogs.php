<?php
/**
 * @Date: 2024/11/27 16:19
 */
declare(strict_types=1);
namespace app\sys\controller\system;

use app\common\services\system\LoginLogsService;
use app\sys\controller\Base;
use think\facade\{
    App,
    Config
};

/**
 * 登录记录
 * Class LoginLogs
 * @package app\sys\controller\system
 */
class LoginLogs extends Base
{
    /**
     * LoginLogsService constructor.
     */
    public function __construct(App $app, LoginLogsService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }


    /**
     * 列表
     * @noAuth(true)
     * @method(GET)
     */
    public function list()
    {
        $data = $this->request->getMore([
            ['id', 0],
            ['source', 'admin'],
        ]);
        $this->success($this->service->getList($data));
    }
}