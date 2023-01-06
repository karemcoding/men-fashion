<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php'
);

return [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'runtimePath' => dirname(dirname(dirname(__DIR__))) . '/api/runtime',
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'enableCsrfValidation' => false,
            'enableCsrfCookie' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'api\models\Customer',
            'enableSession' => false,
            'loginUrl' => null,
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
