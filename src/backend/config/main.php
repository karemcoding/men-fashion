<?php

use common\models\User;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'runtimePath' => dirname(dirname(dirname(__DIR__))) . '/admin/runtime',
    'bootstrap' => ['log'],
    'modules' => [
        'gridviewKrajee' => [
            'class' => '\kartik\grid\Module',
            'bsVersion' => '4.x',
        ],
        'gridview' => [
            'class' => 'kartik\grid\Module',
            // other module settings
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => User::class,
        ],
        'session' => [
            'name' => 'session-backend',
        ],
        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => TRUE,
            'identityCookie' => [
                'name' => '_identity-backend',
                'httpOnly' => TRUE
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => '\yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:[a-z0-9\-]+>/<id:\d+>' => '<controller>/index',
                '<controller:[a-z0-9\-]+>' => '<controller>/index',
                '<controller:[a-z0-9\-]+>/<action:[a-z0-9\-]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[a-z0-9\-]+>/<action:[a-z0-9\-]+>' => '<controller>/<action>',
                '<module:[a-z0-9\-]+>/<controller:[a-z0-9\-]+>/<id:\d+>' => '<module>/<controller>/index',
                '<module:[a-z0-9\-]+>/<controller:[a-z0-9\-]+>' => '<module>/<controller>/index',
                '<module:[a-z0-9\-]+>/<controller:[a-z0-9\-]+>/<action:[a-z0-9\-]+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:[a-z0-9\-]+>/<controller:[a-z0-9\-]+>/<action:[a-z0-9\-]+>' => '<module>/<controller>/<action>',
            ]
        ],
    ],
    'params' => $params,
];
