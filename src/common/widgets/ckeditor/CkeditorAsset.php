<?php

namespace common\widgets\ckeditor;

use yii\web\AssetBundle;

/**
 * Class CkeditorAsset
 *
 * @package common\widgets\ckeditor
 */
class CkeditorAsset extends AssetBundle
{

    public $js = [
        'ckeditor.js',
        'adapter.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';
        parent::init();
    }
}