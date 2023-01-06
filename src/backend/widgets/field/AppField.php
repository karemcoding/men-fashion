<?php

namespace backend\widgets\field;

use yii\bootstrap4\ActiveField;

/**
 * Class AppField
 * @package backend\widgets\field
 */
class AppField extends ActiveField
{
    public $template;
    public $icon;
    public $positionCssClass = "input-group-prepend";

    public function init()
    {
        parent::init();
        $this->setTemplate();
    }

    protected function setTemplate()
    {
        $this->template = <<<HTML
        {label}\n
        {hint}\n
        <div class="input-group input-group-merge">
            {input}\n
            <div class='$this->positionCssClass'>
                <div class="input-group-text">
                    $this->icon
                </div>
            </div>
            {error}
        </div>
HTML;
    }
}