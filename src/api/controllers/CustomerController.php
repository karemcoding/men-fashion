<?php

namespace api\controllers;

use api\models\Customer;
use api\models\CustomerForm;
use Yii;
use yii\base\Exception;
use yii\console\ExitCode;
use yii\filters\auth\HttpBearerAuth;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;

/**
 * Class CustomerController
 * @package api\controllers
 */
class CustomerController extends ActiveController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = ['class' => HttpBearerAuth::class];
        return $behaviors;
    }

    /**
     * @return array|IdentityInterface|null
     * @throws Exception
     */
    public function actionUpdate()
    {
        $post = $this->request->post();
        if ($post) {
            /** @var Customer $customer */
            $customer = Yii::$app->user->identity;
            $form = new CustomerForm();
            $form->scenario = CustomerForm::SCENARIO_UPDATE;
            $form->setAttributes($customer->getAttributes(), FALSE);
            $form->setAttributes($post);
            if ($form->update()) {
                return [
                    'status' => 200,
                    'errors' => NULL,
                ];
            }
            return [
                'status' => ExitCode::UNAVAILABLE,
                'errors' => $form->errors,
            ];
        }
        $exception = new UnauthorizedHttpException();
        $this->response->setStatusCodeByException($exception);
        return [
            'code' => $exception->getCode(),
            'name' => Yii::$app->errorHandler->getExceptionName($exception),
            'message' => $exception->getMessage(),
        ];
    }
}