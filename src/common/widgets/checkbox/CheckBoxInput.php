<?php

namespace common\widgets\checkbox;

use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Class CheckBoxInput
 * Property options là một mảng các giá trị
 * chúng ta truyền vào.
 * ví dụ: <input class="class-input" id="id-input" attribute=>"attribute-input">
 * thì var_dump($this->options)
 * ta có:
 * options => [
 *      'class'=>'class-input',
 *      'id'=>'id-input',
 *      'attribute'=>'attribute-input'
 * ]
 */
class CheckBoxInput extends InputWidget
{

    public $inactive_value = 0;

    public $active_value = 1;

    public $name = 'checkbox-input';

    public $checked;

    public $disabled = FALSE;

    public function run()
    {
        $input_id = $this->options['id'];
        $html = Html::beginTag('div', [
            'class' => 'custom-control custom-checkbox',
        ]);
        if ($this->hasModel()) {
            $class = explode("\\", get_class($this->model));
            $model = end($class);
            $html .= Html::hiddenInput("{$model}[$this->attribute]", $this->inactive_value);
            $html .= Html::checkbox("{$model}[$this->attribute]",
                $this->checked,
                [
                    'class' => 'custom-control-input',
                    'id' => $input_id,
                    'value' => $this->active_value,
                    'disabled' => $this->disabled
                ]);
            $html .= Html::label(NULL, $input_id, ['class' => 'custom-control-label']);
        } else {
            $html .= Html::hiddenInput($this->name ?? NULL, $this->inactive_value);
            $html .= Html::checkbox($this->name ?? NULL,
                $this->checked ?? FALSE,
                [
                    'class' => 'custom-control-input',
                    'id' => $input_id,
                    'value' => $this->active_value,
                    'disabled' => $this->disabled
                ]);
            $html .= Html::label(NULL, $input_id, ['class' => 'custom-control-label']);
        }
        $html .= Html::endTag('div');
        echo $html;
    }
}