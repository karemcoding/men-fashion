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
class ReverseAction extends Action
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
                ->setUrl("https://nominatim.openstreetmap.org/reverse")
                ->setMethod('GET')
                ->setData([
                    'lat' => $post['lat'],
                    'lon' => $post['lng'],
                    'format' => 'jsonv2',
                    //This's magic, thêm email để nominatim cho phép sử dụng api
                    'email' => 'beehamchoi@yopmail.com'
                ])
                ->send();
            if ($response->statusCode == 200) {
                try {
                    $address = Json::decode($response->content);
                    return [
                        'code' => 200,
                        'address' => $address['display_name'] ?? Yii::t('common', 'Not Found'),
                        'lat' => $post['lat'],
                        'lng' => $post['lng']
                    ];
                } catch (\Exception $exception) {

                }
            }
        }
        return ['code' => 403];
    }
}