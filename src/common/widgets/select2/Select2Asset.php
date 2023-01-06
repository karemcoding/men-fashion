<?php


namespace common\widgets\select2;

use yii\web\AssetBundle;

/**
 * Class Select2Asset
 *
 * @package common\widgets\select2
 */
class Select2Asset extends AssetBundle
{

    public $sourcePath = '@common/widgets/select2';

    public $js = [
        'js/select2.min.js',
    ];
    public $css = [
        'css/select2.min.css',
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
