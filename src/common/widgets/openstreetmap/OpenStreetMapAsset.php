<?php

namespace common\widgets\openstreetmap;

use yii\web\AssetBundle;

/**
 * Class OpenStreetMapAsset
 *
 * @package common\widgets\openstreetmap
 */
class OpenStreetMapAsset extends AssetBundle
{

    public $js = [
        'js/leaflet.js',
        'js/Leaflet.fullscreen.js',
    ];

    public $css = [
        'css/leaflet.css',
        'css/leaflet.fullscreen.css',
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
