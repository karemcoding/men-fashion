<?php

namespace common\widgets\toggle;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * Class Toggle
 *
 * @package common\widgets\toggle
 */
class ToggleInput extends InputWidget
{

    public $inactive_value = 0;

    public $active_value = 10;

    public $name = 'toggle-input';

    public $checked;

    public $disabled = FALSE;

    public $action = [
        'url' => '',
        'request_type' => '',
        'data_type' => '',
        'sender' => '',
    ];

    public $clientScript = NULL;

    /**
     * @return string|void
     */
    public function run()
    {
        $input_id = $this->options['id'];
        $html = Html::beginTag('div', [
            'class' => 'custom-control custom-switch',
        ]);
        if ($this->hasModel()) {
            $class = explode("\\", get_class($this->model));
            $model = end($class);

            $html .= Html::hiddenInput("{$model}[$this->attribute]", $this->inactive_value);

            $html .= Html::checkbox("{$model}[$this->attribute]",
                $this->checked,
                ArrayHelper::merge($this->configCheckbox($input_id), $this->_getAction())
            );

            $html .= Html::label(NULL, $input_id, ['class' => 'custom-control-label']);

        } else {
            $html .= Html::hiddenInput($this->name ?? NULL, $this->inactive_value);

            $html .= Html::checkbox($this->name ?? NULL,
                $this->checked ?? FALSE,
                ArrayHelper::merge($this->configCheckbox($input_id), $this->_getAction())
            );

            $html .= Html::label(NULL, $input_id, ['class' => 'custom-control-label']);
        }
        $html .= Html::endTag('div');
        echo $html;

        if (!empty($this->action['url'])) {
            $this->_registerJs();
        }
        if (!empty($this->clientScript)) {
            $this->_registerClientScript();
        }
    }

    /**
     * @inheritDoc
     */
    private function _registerJs()
    {
        $js = <<<JS
				jQuery(document).on('change', '.switch-driver', function(){
				    let url = jQuery(this).attr('url');
				    let data = jQuery(this).attr('sender');
				    let type = jQuery(this).attr('request_type');
				    let dataType = jQuery(this).attr('data_type');
				    jQuery.ajax({
				    	url: url,
				    	data: {request:data},
				    	type: type,
				    	dataType: dataType
				    })
				    .done()
				    .fail()
				});
JS;
        $this->view->registerJs($js, View::POS_READY);
    }

    /**
     * @return array
     */
    private function _getAction()
    {
        return !empty($this->action['url']) ? $this->action : [];
    }

    /**
     * @param $input_id
     *
     * @return array
     */
    private function configCheckbox($input_id)
    {
        return
            [
                'class' => 'custom-control-input switch-driver',
                'id' => $input_id,
                'value' => $this->active_value,
                'disabled' => $this->disabled
            ];
    }

    /**
     * @inheritDoc
     */
    private function _registerClientScript()
    {
        $js = $this->clientScript;
        $this->view->registerJs($js, View::POS_READY, 'switcher-client');
    }
}