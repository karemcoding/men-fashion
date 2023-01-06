<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=advancebase',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'tablePrefix' => 'ad_'
        ],
        'view' => [
            'class' => 'common\util\View',
            'compress' => false,
        ],
    ],
];
