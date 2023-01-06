<?php

namespace backend\widgets\app;

use yii\web\AssetBundle;


class AppLoginAsset extends AssetBundle
{

    public $css = [
        'css/login.css',
    ];

    public $depends = [
        AppAsset::class
    ];

    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';
        parent::init();
    }
}
