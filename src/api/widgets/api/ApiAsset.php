<?php

namespace api\widgets\api;

use common\widgets\fontawesome\FontAwesomeAsset;
use yii\web\AssetBundle;

/**
 * Class ApiAsset
 * @package api\widgets\app
 */
class ApiAsset extends AssetBundle
{
    public $css = [
        'css/api.css',
    ];

    public $depends = [
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
