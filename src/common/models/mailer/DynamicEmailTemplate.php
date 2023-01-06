<?php

namespace common\models\mailer;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class EmailTemplate
 * @package common\models\mailer
 *
 * @property-read array $emailParams
 * @property-read array $templateParams
 */
class DynamicEmailTemplate extends Model
{
    public $key;

    public $name;

    public $description;

    public $params = [];

    /**
     * @return array
     */
    public function getTemplateParams(): array
    {
        return ArrayHelper::getColumn($this->params, function ($data) {
            return "[$data]";
        });
    }

    /**
     * @return array
     */
    public function getEmailParams(): array
    {
        return ArrayHelper::map($this->params, function ($data) {
            return $data;
        }, function ($data) {
            return "[$data]";
        });
    }
}