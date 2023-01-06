<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 11:51 AM 6/19/2021
 * @projectName baseProject by ANDY
 */

namespace backend\widgets\printjs;

use common\widgets\fontawesome\FontAwesomeAsset;
use common\widgets\fontfeather\FontFeatherAsset;
use yii\web\AssetBundle;

/**
 * Class PrintJsAsset
 * @package backend\widgets\printjs
 */
class PrintJsAsset extends AssetBundle
{
    public $js = [
        'print.min.js',
    ];

    public $css = [
        'print.min.css',
    ];

    public $depends = [
        FontFeatherAsset::class,
        FontAwesomeAsset::class,
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';
        parent::init();
    }
}