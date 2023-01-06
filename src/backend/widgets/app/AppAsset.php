<?php

namespace backend\widgets\app;

use common\widgets\fontawesome\FontAwesomeAsset;
use common\widgets\fontfeather\FontFeatherAsset;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{

    public $css = [
        'css/theme.min.css',
        'css/app.css'
    ];

    public $js = [
        'js/theme.min.js',
        'js/main.js',
    ];

    public $depends = [
        FontFeatherAsset::class,
        FontAwesomeAsset::class,
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
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
