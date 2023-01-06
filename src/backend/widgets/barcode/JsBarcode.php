<?php

namespace backend\widgets\barcode;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class PrintJs
 * @package backend\widgets\printjs
 */
class JsBarcode extends Widget
{
    public $pluginOptions = [];

    public $options = [];

    public $content;

    public function init()
    {
        parent::init();
        $this->options['id'] = $this->id;
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->registerJs();
        return Html::tag('canvas', NULL, $this->options);
    }

    public function registerJs()
    {
        $view = $this->getView();
        $id = $this->id;
        $content = $this->content;
        $clientOptions = Json::encode($this->pluginOptions);
        JsBarcodeAsset::register($view);
        $js = ";jQuery('#$id').JsBarcode('$content',$clientOptions);";
        $view->registerJs($js);
    }
}