<?php

namespace api\controllers;

use Exception;
use Yii;
use yii\base\Action;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class ActiveController
 *
 * @package api\controllers
 */
class ActiveController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => NULL,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => [],
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['GET', 'HEAD'],
                    'view' => ['GET', 'HEAD'],
                    'create' => ['POST'],
                    'update' => ['PUT', 'PATCH', 'POST'],
                    'delete' => ['DELETE'],
                ],
            ],
            'rateLimiter' => [
                'class' => RateLimiter::class,
            ],
        ];
    }

    /**
     * @param Action $action
     * @param mixed $result
     * @return mixed
     */
    public function afterAction($action, $result)
    {
        $result = [
            'status' => $this->response->statusCode,
            'message' => Yii::t('common', "Kết nối với server thành công!"),
            'data' => $result,
        ];
        return parent::afterAction($action, $result);
    }

    /**
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        try {
            return parent::beforeAction($action);
        } catch (Exception $exception) {
            $this->response->data = [
                'status' => $this->response->setStatusCodeByException($exception)->statusCode,
                'data' => [
                    'code' => $exception->getCode(),
                    'name' => Yii::$app->errorHandler->getExceptionName($exception),
                    'message' => $exception->getMessage(),
                ],
            ];
            return FALSE;
        }
    }
}
