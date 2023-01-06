<?php

use yii\mutex\MysqlMutex;
use yii\queue\db\Queue;

return [
    'name' => 'common',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'bootstrap' => ['languageSelector', 'mailerQueue'],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'en-US',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'view' => [
            'class' => 'common\util\View',
            'compress' => TRUE,
        ],
        'setting' => [
            'class' => 'common\util\Setting',
        ],
        'languageSelector' => [
            'class' => 'common\util\LanguageSelector',
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'],
                ],
                'yii\bootstrap4\BootstrapAsset' => [
                    'css' => [YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css'],
                ],
                'yii\bootstrap4\BootstrapPluginAsset' => [
                    'js' => [YII_ENV_DEV ? 'js/bootstrap.bundle.js' : 'js/bootstrap.bundle.min.js'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'common*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'dd/MM/yyyy',
            'datetimeFormat' => 'HH:mm dd/MM/yyyy',
            'timeZone' => 'Asia/Ho_Chi_Minh',
            'currencyCode' => 'VND',
            'currencyDecimalSeparator' => ',',
            'nullDisplay' => '',
        ],
        'mailerQueue' => [
            'class' => Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'mailer',
            'mutex' => MysqlMutex::class,
            'attempts' => 5,
            'ttr' => 60,//Second
            'deleteReleased' => FALSE,
        ],
    ],
];
