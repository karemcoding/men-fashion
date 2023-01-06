<?php

namespace backend\widgets\qrcode;

use yii\web\AssetBundle;

/**
 * Class QRCodeAsset
 * @package backend\widgets\qrcode
 */
class QRCodeAsset extends AssetBundle
{
    public $js = [
        'qrcode.min.js',
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