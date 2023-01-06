<?php

namespace api\controllers;

use api\models\Customer;
use api\models\CustomerForm;
use api\models\ForgotPasswordForm;
use api\models\LoginForm;
use common\util\Status;
use Yii;
use yii\base\Exception;
use yii\console\ExitCode;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UnauthorizedHttpException;

/**
 * Class SiteController
 *
 * @package api\controllers
 */
class SiteController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = ['class' => HttpBearerAuth::class];
        $behaviors['authenticator']['except'] = ['login', 'login-external', 'error', 'signup',
            'reset-password-code', 'reset-password'];
        $behaviors['verbFilter']['actions'] = [
            'login' => ['POST'],
            'signup' => ['POST'],
            'login-external' => ['POST'],
            'reset-password-code' => ['POST'],
            'reset-password' => ['POST'],
        ];
        return $behaviors;
    }

    /**
     * @return null
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        return [
            'code' => $exception->getCode(),
            'name' => Yii::$app->errorHandler->getExceptionName($exception),
            'message' => $exception->getMessage(),
        ];
    }

    /**
     * @return array
     */
    public function actionLogin()
    {
        $post = Yii::$app->request->post();
        $form = new LoginForm();
        $form->setAttributes($post);
        if ($user = $form->login()) {
            return [
                'status' => 200,
                'token' => $user->auth_key,
                'user' => $user,
            ];
        }
        return [
            'status' => ExitCode::UNAVAILABLE,
            'errors' => $form->firstErrors,
        ];
    }

    /**
     * @return Customer|array|mixed|null
     * @throws Exception
     */
    public function actionSignup()
    {
        $post = Yii::$app->request->post();
        if ($post) {
            $form = new CustomerForm(['status' => Status::STATUS_ACTIVE]);
            $form->setAttributes($post);
            $form->scenario = CustomerForm::SCENARIO_CREATE;
            if ($form->create()) {
                return [
                    'status' => 200,
                    'errors' => NULL,
                    'user' => Customer::findOne([
                        'email' => $post['email'] ?? '',
                        'phone' => $post['phone'] ?? '',
                    ]),
                ];
            }
            return [
                'status' => ExitCode::UNAVAILABLE,
                'errors' => $form->firstErrors,
            ];
        }
        return $this->throwError401();
    }

    /**
     * @return array
     */
    public function actionResetPasswordCode()
    {
        $post = Yii::$app->request->post();
        $form = new ForgotPasswordForm();
        $form->setAttributes($post);
        $form->scenario = ForgotPasswordForm::SCENARIO_GET_CODE;
        if ($form->sendMail()) {
            return [
                'status' => 200,
                'message' => Yii::t('common', 'Email sent.'),
                'errors' => NULL,
            ];
        }
        return [
            'status' => ExitCode::UNAVAILABLE,
            'errors' => $form->firstErrors,
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function actionResetPassword()
    {
        $post = Yii::$app->request->post();
        $form = new ForgotPasswordForm();
        $form->setAttributes($post);
        if ($form->setPass()) {
            return [
                'status' => 200,
                'message' => Yii::t('common', 'Thành công.'),
                'errors' => NULL,
            ];
        }
        return [
            'status' => ExitCode::UNAVAILABLE,
            'errors' => $form->firstErrors,
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function actionLoginExternal()
    {
        $post = $this->request->post();
        if (!empty($post['email'])) {
            $user = Customer::find()->andWhere(['email' => $post['email']])->active()->one();
            if (!$user) {
                $form = new CustomerForm(['status' => Status::STATUS_ACTIVE]);
                $form->setAttributes($post);
                $form->scenario = CustomerForm::SCENARIO_SIGNUP_EXTERNAL;
                if ($form->create()) {
                    $customer = Customer::find()->andWhere(['email' => $form->email])->one();
                    return [
                        'token' => $customer->auth_key,
                        'user' => $customer,
                    ];
                }
                return $this->throwError401();
            }
            return [
                'token' => $user->auth_key,
                'user' => $user,
            ];
        }
        return $this->throwError401();
    }

    /**
     * @return array
     */
    public function throwError401()
    {
        $exception = new UnauthorizedHttpException();
        $this->response->setStatusCodeByException($exception);
        return [
            'code' => $exception->getCode(),
            'name' => Yii::$app->errorHandler->getExceptionName($exception),
            'message' => $exception->getMessage(),
        ];
    }
}
