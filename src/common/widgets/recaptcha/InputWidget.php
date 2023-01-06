<?php

namespace common\widgets\recaptcha;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\View;
use yii\widgets\InputWidget as BaseInputWidget;

/**
 * Class InputWidget
 *
 * @package common\widgets\recaptcha
 */
class InputWidget extends BaseInputWidget
{

    public $site_key = NULL;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if ($this->site_key === NULL) {
            $this->site_key = Yii::$app->params['recaptcha']['site_key'] ?? NULL;

            if (empty($this->site_key)) {
                throw new InvalidConfigException("Required reCAPTCHA key params aren't set.");
            }
        }

        $this->field->template = '{input}';

        parent::init();
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $this->view->registerJsFile('https://www.google.com/recaptcha/api.js?render=' . $this->site_key,
            ['position' => View::POS_HEAD], 'recaptcha');

        $field_id = $this->options['id'] ?? $this->id;
        $js = <<<JS
	grecaptcha.ready(function() {
	    grecaptcha.execute('{$this->site_key}', {action: 'submit'}).then(function(token) {
	       $('#{$field_id}').val(token);
	    });
	});
JS;
        $this->view->registerJs($js);

        return $this->renderInputHtml('hidden');
    }
}
