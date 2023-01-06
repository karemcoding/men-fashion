<?php

namespace common\widgets\fontawesome;

use yii\web\AssetBundle;

/**
 * Class FontAwesomeAsset
 *
 * @package common\widgets\fontawesome
 */
class FontAwesomeAsset extends AssetBundle
{

    public $css = [
        'css/font-awesome.min.css',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'fontawesome';
        parent::init();
    }
}
