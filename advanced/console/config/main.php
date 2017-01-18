<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],  
        'websocket' => [
            'class' => 'morozovsk\yii2websocket\Connection',
            'servers' => [
                'chat3' => [
                    'class' => 'common\components\server\Chat3WebsocketDaemonHandler',
                    'pid' => dirname(__DIR__).'/tmp/websocket_chat.pid',
                    'websocket' => 'tcp://127.0.0.1:8004',
                    'localsocket' => 'tcp://127.0.0.1:8010',
                    //'master' => 'tcp://127.0.0.1:8020',
                    //'eventDriver' => 'event'
                ]
            ],
        ],
    ],
    'controllerMap' => [
        'websocket' => 'common\components\MainWebsocketController'
    ],
    'params' => $params,
];
