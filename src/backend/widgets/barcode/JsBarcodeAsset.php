<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 11:51 AM 6/19/2021
 * @projectName baseProject by ANDY
 */

namespace backend\widgets\barcode;

use yii\web\AssetBundle;

/**
 * Class JsBarcodeAsset
 * @package backend\widgets\printjs
 */
class JsBarcodeAsset extends AssetBundle
{
    public $js = [
        'JsBarcode.all.min.js',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'dist';
        parent::init();
    }
}