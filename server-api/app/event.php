<?php
// 事件定义文件
return [
    'bind'      => [
    ],

    'listen'    => [
        'AppInit'  => [
            'app\\common\\event\\InitConfig',
        ],
        'HttpRun'  => [
            'app\\common\\event\\InitRoute'
        ],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
    ],
    'subscribe' => [
    ],
];
