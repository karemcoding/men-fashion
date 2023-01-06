<?php

namespace backend\widgets\printjs;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class PrintJs
 * @package backend\widgets\printjs
 */
class PrintJs extends Widget
{
    public $btnContent = 'PrintJs';

    public $pluginOptions = [];

    public $options = ['class' => 'btn btn-primary'];

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
        return Html::button($this->btnContent, $this->options);
    }

    public function registerJs()
    {
        $view = $this->getView();
        $id = $this->id;
        $clientOptions = Json::encode($this->pluginOptions);
        PrintJsAsset::register($view);
        $js = "jQuery(document).on('click','#$id',function(event) {printJS($clientOptions)});";
        $view->registerJs($js);
    }
}