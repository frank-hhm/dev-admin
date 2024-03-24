<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:30
 */
declare(strict_types=1);

namespace app\sys\controller;
use app\BaseController;
/**
 * 控制器基础类
 */
class Base extends BaseController
{

    /**
     * 当前登陆管理员信息
     */
    protected mixed $adminInfo;

    /**
     * 当前登陆管理员ID
     */
    protected int $adminId;

    // 初始化
    protected function initialize(): void
    {
        $this->adminId = $this->request->adminId() ?? 0;
        $this->adminInfo = $this->request->adminInfo() ?? [];
        $this->checkAuth($this->request->roles() ?? []);
    }
}
