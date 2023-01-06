<?php

namespace common\widgets\fontfeather;

use yii\web\AssetBundle;

/**
 * Class FontFeatherAsset
 *
 * @package common\widgets\fontfeather
 */
class FontFeatherAsset extends AssetBundle
{

    public $css = [
        'feather/feather.min.css',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'fonts';
        parent::init();
    }
}
