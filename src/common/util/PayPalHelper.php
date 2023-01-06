<?php


namespace common\util;

use common\models\settings\PayPal;
use PayPalCheckoutSdk\Core\PayPalEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use ReflectionException;
use yii\base\InvalidConfigException;

/**
 * Class PayPalEnvironment
 * @package common\util
 */
class PayPalHelper extends PayPalEnvironment
{
    private $clientId;
    private $clientSecret;
    private $apiUrl;
    public $merchantId;

    /**
     * PayPalHelper constructor.
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function __construct()
    {
        /** @var PayPal $setting */
        $setting = AppHelper::setting()->model(PayPal::class);
        $this->clientId = $setting->appClientId;
        $this->clientSecret = $setting->appSecret;
        $this->apiUrl = $setting->apiBaseUrl;
        $this->merchantId = $setting->merchantId;
    }

    /**
     * @return string
     */
    public function authorizationString()
    {
        return base64_encode($this->clientId . ":" . $this->clientSecret);
    }

    /**
     * @return string
     */
    public function baseUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @return PayPalHttpClient
     */
    public static function credentials()
    {
        $environment = new PayPalHelper();
        return (new PayPalHttpClient($environment));
    }
}