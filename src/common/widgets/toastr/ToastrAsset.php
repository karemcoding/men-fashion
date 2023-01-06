<?php

namespace common\widgets\toastr;

use yii\web\AssetBundle;

/**
 * Class ToastrAsset
 *
 * @package app\widgets\toastr
 */
class ToastrAsset extends AssetBundle
{

    public $js = [
        'toastr.min.js',
    ];

    public $css = [
        'toastr.min.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';
        parent::init();
    }
}