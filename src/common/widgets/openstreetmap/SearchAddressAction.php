<?php

namespace common\widgets\openstreetmap;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class SearchAddressAction
 * @package common\widgets\openstreetmap
 */
class SearchAddressAction extends Action
{
    /** @var Client */
    public $httpClient;

    public function init()
    {
        parent::init();
        $this->httpClient = new Client();
    }

    /**
     * @return array|int[]
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function run()
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }
        $post = Yii::$app->request->post();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($post) {
            $response = $this->httpClient->createRequest()
                ->setUrl("https://nominatim.openstreetmap.org/search")
                ->setMethod('GET')
                ->setData([
                    'q' => $post['q'],
                    'format' => 'jsonv2',
                    'email' => 'beehamchoi@yopmail.com'
                ])
                ->send();
            if ($response->statusCode == 200) {
                try {
                    $addressList = Json::decode($response->content);
                    $address = reset($addressList);
                    return [
                        'code' => 200,
                        'lat' => $address['lat'] ?? 1.3408630000000001,
                        'lng' => $address['lon'] ?? 103.83039182212079,
                        'address' => $address['display_name'] ?? $post['q']
                    ];
                } catch (\Exception $exception) {

                }
            }
        }
        return ['code' => 403];
    }
}