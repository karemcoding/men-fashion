<?php

namespace backend\widgets\chart;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class Chart
 *
 * @package backend\widgets\chart
 */
class Chart extends Widget
{

    const MONTHS = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    const CHART_BAR = 'bar';

    const CHART_DOUGHNUT = 'doughnut';

    const CHART_BAR_HORIZONTAL = 'horizontalBar';

    const CHART_POLAR = 'polarArea';

    const CHART_LINE = 'line';

    public $options = [];

    public $pluginOptions = [];

    public $data = [];

    public $type = self::CHART_BAR;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if ($this->type === NULL) {
            throw new InvalidConfigException("The 'type' option is required");
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * @return string|void
     */
    public function run()
    {
        parent::run();

        echo Html::tag('div', Html::tag('canvas', '', $this->options),
            ['class' => 'position-relative bg-white p-2']);
        $this->registerClientScript();
    }

    /**
     * Register javascript
     */
    protected function registerClientScript()
    {
        $id = $this->options['id'];
        $type = $this->type;
        $view = $this->getView();
        $data = !empty($this->data) ? Json::encode($this->data) : '{}';
        $options = !empty($this->pluginOptions) ? Json::encode($this->pluginOptions) : '{}';
        ChartAsset::register($view);
        $js = ";var chartJS_{$id} = new Chart(document.getElementById('{$id}'), {type: '{$type}', data: {$data}, options: {$options}});";
        $view->registerJs($js);
    }
}
