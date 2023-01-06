<?php

namespace common\widgets\flatpickr;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Class Flatpickr
 *
 * @package common\widgets\flatpickr
 */
class Flatpickr extends InputWidget
{

    public $clientOptions = [];

    public $theme;

    public $locale;

    public $plugins = [];

    public $name = 'flatpickr-input';

    public function init()
    {
        parent::init();
        if (empty($this->locale)) {
            $language = explode('-', Yii::$app->language);
            $this->locale = $language[0];
        }
    }

    /**
     * @return string|void
     */
    public function run()
    {
        parent::run();
        $this->_registerClientScript();
        echo $this->_html();
    }

    private function _registerClientScript()
    {
        $this->clientOptions['theme'] = $this->theme;
        $this->clientOptions['locale'] = $this->locale;
        if (!empty($this->plugins) && is_array($this->plugins)) {
            $plugins = [];
            foreach ($this->plugins as $key => $item) {
                $options = Json::encode($this->plugins[$key]);
                $plugins[] = "$key($options)";
            }

            $this->clientOptions['plugins'] = new JsExpression('[new ' . implode(', ',
                    $plugins) . ']');
        }

        $view = $this->getView();
        $asset = FlatpickrAsset::register($view);
        $asset->locale = $this->locale;
        $asset->theme = $this->theme;
        $asset->plugins = $this->plugins;
        $id = $this->options['id'] ?? $this->id;
        $config = !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '';
        $js = ";jQuery('#$id').flatpickr($config);";
        $view->registerJs($js);
    }

    /**
     * @return string
     */
    private function _html()
    {
        if ($this->hasModel()) {
            $input = Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::textInput($this->name, $this->value, $this->options);
        }

        return $input;
    }
}