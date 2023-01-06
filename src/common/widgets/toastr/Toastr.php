<?php

namespace common\widgets\toastr;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;

/**
 * Class Toastr
 *
 * @package app\widgets\toastr
 *
 * @property-read array $options
 */
class Toastr extends Widget
{

    const SUCCESS = 'success';

    const INFO = 'info';

    const ERROR = 'error';

    const WARNING = 'warning';

    const ON_SHOWN = 'onShown';

    const ON_HIDDEN = 'onHidden';

    const ON_CLICK = 'onClick';

    const ON_CLOSE_CLICK = 'onCloseClick';

    public $pluginOptions = [];

    public $message = '';

    public $title = '';

    public $type = 'success';

    public $onEvents = [];
    /**
     * @var array
     * when['selector']
     * when['event']
     */
    public $when = [];


    /*
     * Run Widget
     */
    public function run()
    {
        $view = $this->view;
        $this->registerAsset($view);
        $message = !empty($this->title) ? "'$this->message','$this->title'" : "'$this->message',''";
        $js = empty($this->when) ? $this->runWithoutWhen($message) : $this->runWithWhen($message);
        $view->registerJs($js);
    }

    /**
     * @param $message
     *
     * @return string
     */
    protected function runWithoutWhen($message)
    {
        return "toastr.$this->type($message,$this->options);";
    }

    /**
     * @param $message
     *
     * @return string
     */
    protected function runWithWhen($message)
    {
        $selector = $this->when['selector'];
        $event = $this->when['event'];

        return "jQuery(document).on('$event','$selector',function() {toastr.$this->type($message,$this->options)});";
    }

    /**
     * @param View $view
     */
    protected function registerAsset(View $view)
    {
        ToastrAsset::register($view);
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    protected function getOptions()
    {
        $plugin_options = $this->pluginOptions;
        if ($this->onEvents) {
            foreach ($this->onEvents as $event => $callback) {
                if (!($callback instanceof JsExpression)) {
                    throw new InvalidConfigException('The \'Callback\' property must be of type yii\\web\\JsExpression');
                } else {
                    $plugin_options[$event] = $callback;
                }
            }
        }

        return Json::encode($plugin_options);
    }
}