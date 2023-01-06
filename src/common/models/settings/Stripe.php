<?php

namespace common\models\settings;

use Yii;

class Stripe extends Setting
{
    public $secretKey;
    public $publishableKey;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['secretKey', 'publishableKey'], 'string'],
            [['secretKey'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'secretKey' => Yii::t('common', 'Secret Key'),
            'publishableKey' => Yii::t('common', 'Publishable Key'),
        ];
    }
}