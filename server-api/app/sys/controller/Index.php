<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:30
 */
declare(strict_types=1);

namespace app\sys\controller;

class Index extends Base
{
    public function index()
    {
        return $this->success();
    }
}

