<?php

namespace common\widgets\ckeditor;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * Class CkeditorInput
 *
 * @package common\widgets\ckeditor
 * @property JsExpression $clientCallback
 */
class CkeditorInput extends InputWidget
{

    const SKIN_OFFICE_2013 = 'office2013';

    const SKIN_KAMA = 'kama';

    public $clientOptions = [];

    public $clientCallback;

    public $name = 'ckeditor-input';

    /**
     * @return string|void
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $this->_renderInput();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    private function _renderInput()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->options['value'] ?? NULL, $this->options);
        }
        $this->_registerJs();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    private function _registerJs()
    {
        $view = $this->view;
        $this->registerBundle($view);
        $id = $this->options['id'];
        $client_options = Json::encode($this->clientOptions);
        $client_callback = '';

        if (!empty($this->clientCallback)) {
            if (!($this->clientCallback instanceof JsExpression)) {
                throw new InvalidConfigException('The \'clientCallback\' property must be of type yii\\web\\JsExpression');
            } else {
                $client_callback = "function(){{$this->clientCallback->expression}},";
            }
        }

        $js = ";jQuery('#$id').ckeditor($client_callback $client_options);";
        $view->registerJs($js, View::POS_READY, uniqid('ckeditor_'));
    }

    /**
     * @param \yii\web\View $view
     */
    protected function registerBundle(View $view)
    {
        CkeditorAsset::register($view);
    }
}
