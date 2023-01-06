<?php

namespace backend\widgets\qrcode;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Class QRCode
 * @package backend\widgets\qrcode
 * https://github.com/davidshimjs/qrcodejs
 */
class QRCode extends Widget
{
    public $pluginOptions = [];

    public $options = [];

    public function init()
    {
        parent::init();
        $this->options['id'] = $this->id;
        if (!empty($this->pluginOptions['correctLevel'])) {
            $this->pluginOptions['correctLevel'] = new JsExpression($this->pluginOptions['correctLevel']);
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->registerJs();
        return Html::tag('div', NULL, $this->options);
    }

    public function registerJs()
    {
        $view = $this->getView();
        $id = $this->id;
        $clientOptions = Json::encode($this->pluginOptions);
        QRCodeAsset::register($view);
        $js = ";new QRCode(document.getElementById('$id'),$clientOptions);";
        $view->registerJs($js);
    }
}