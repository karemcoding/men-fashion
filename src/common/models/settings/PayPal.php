<?php

namespace common\models\settings;

use Yii;

/**
 * Class PayPal
 * @package common\models\settings
 */
class PayPal extends Setting
{
    public $appClientId;
    public $appSecret;
    public $apiBaseUrl;
    public $merchantId;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['appClientId', 'appSecret', 'merchantId'], 'string'],
            [['apiBaseUrl'], 'url'],
            [['apiBaseUrl', 'appSecret', 'appClientId'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'appClientId' => Yii::t('common', 'App Client Id'),
            'appSecret' => Yii::t('common', 'App Secret'),
            'apiBaseUrl' => Yii::t('common', 'API Url'),
            'merchantId' => Yii::t('common', 'MerchantId'),
        ];
    }
}