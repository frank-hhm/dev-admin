<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'wechat:create_weapp_task'        => 'app\common\command\wechat\open\CreateWeappTaskCommand',
    ],
];
