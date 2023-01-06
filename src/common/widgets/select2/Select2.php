<?php

namespace common\widgets\select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * Class Select2
 *
 * @package common\widgets\select2
 */
class Select2 extends InputWidget
{

    public $items = [];

    public $clientOptions = [];

    public $clientEvents = [];

    public $name = 'select2-input';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->initPlaceholder();
    }

    /**
     * Select2 plugin placeholder check and initialization
     */
    protected function initPlaceholder()
    {
        $multipleSelection = ArrayHelper::getValue($this->options, 'multiple');
        if (!empty($this->options['prompt']) && empty($this->clientOptions['placeholder'])) {
            $this->clientOptions['placeholder'] = $multipleSelection
                ? ArrayHelper::remove($this->options, 'prompt')
                : $this->options['prompt'];

            return NULL;
        } elseif (!empty($this->options['placeholder'])) {
            $this->clientOptions['placeholder'] = ArrayHelper::remove($this->options,
                'placeholder');
        }
        if (!empty($this->clientOptions['placeholder']) && !$multipleSelection) {
            $this->options['prompt'] = is_string($this->clientOptions['placeholder'])
                ? $this->clientOptions['placeholder']
                : ArrayHelper::getValue((array)$this->clientOptions['placeholder'], 'placeholder',
                    '');
        }
    }

    /**
     * @return string|void
     * @inheritDoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->items,
                $this->options);
        } else {
            echo Html::dropDownList($this->name, $this->value, $this->items, $this->options);
        }
        $this->registerClientScript();
    }

    /**
     * @inheritDoc
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $this->registerBundle($view);
        $options = !empty($this->clientOptions)
            ? Json::encode($this->clientOptions)
            : '';
        $id = $this->options['id'];
        $js[] = ";jQuery('#$id').select2($options);";
        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$id').on('$event', $handler);";
            }
        }
        $view->registerJs(implode("\n", $js));
    }

    /**
     * @param \yii\web\View $view
     *
     * @inheritDoc
     */
    protected function registerBundle(View $view)
    {
        Select2Asset::register($view);
    }
}