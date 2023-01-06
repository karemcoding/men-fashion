<?php

namespace backend\controllers;

use backend\util\Permissions;
use common\models\settings\Email;
use common\models\settings\General;
use common\models\settings\PayPal;
use common\models\settings\Stripe;
use common\util\AppHelper;
use ReflectionException;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class SettingController
 * @package backend\controllers
 */
class SettingController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['general'],
                        'allow' => TRUE,
                        'permissions' => [Permissions::SETTING_GENERAL],
                    ],
                    [
                        'actions' => ['email'],
                        'allow' => TRUE,
                        'permissions' => [Permissions::SETTING_EMAIL],
                    ],
                    [
                        'actions' => ['paypal'],
                        'allow' => TRUE,
                        'permissions' => [Permissions::SETTING_PAYPAL],
                    ],
                    [
                        'actions' => ['stripe'],
                        'allow' => TRUE,
                        'permissions' => [Permissions::SETTING_STRIPE],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|Response
     * @throws InvalidConfigException
     * @throws ReflectionException
     * @throws Exception
     */
    public function actionGeneral()
    {
        /** @var General $model */
        $model = AppHelper::setting()->model(General::class);
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->store()) {
                Yii::$app->session->setFlash('success', 'Updated Successful');
                return $this->refresh();
            }
        }

        return $this->render('general', ['model' => $model]);
    }

    /**
     * @return string|Response
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function actionEmail()
    {
        $model = AppHelper::setting()->model(Email::class);
        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Updated Successful');
            return $this->refresh();
        }

        return $this->render('email', ['model' => $model]);
    }

    public function actionPaypal()
    {
        $model = AppHelper::setting()->model(PayPal::class);
        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Updated Successful');
            return $this->refresh();
        }

        return $this->render('paypal', ['model' => $model]);
    }

    public function actionStripe()
    {
        $model = AppHelper::setting()->model(Stripe::class);
        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Updated Successful');
            return $this->refresh();
        }

        return $this->render('stripe', ['model' => $model]);
    }
}
