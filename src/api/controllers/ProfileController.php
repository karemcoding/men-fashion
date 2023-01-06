<?php

namespace api\controllers;

use api\models\ChangePasswordForm;
use api\models\Customer;
use api\models\Profile;
use Yii;
use yii\base\Exception;
use yii\console\ExitCode;
use yii\db\ActiveRecord;
use yii\filters\auth\HttpBearerAuth;

class ProfileController extends ActiveController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    /**
     * @return Customer|array|ActiveRecord|null
     */
    public function actionGet()
    {
        $userId = Yii::$app->user->identity->id;
        return Customer::find()->alias('this')
            ->select(['this.id', 'this.name', 'this.email', 'this.phone', 'this.address'])
            ->andWhere(['this.id' => $userId])
            ->asArray()->one();
    }

    /**
     * @return array|false|null
     */
    public function actionAddress()
    {
        /** @var Customer $user */
        $user = Yii::$app->user->identity;
        $post = $this->request->post('address');
        if (!$post) return NULL;
        $user->address = $post;
        if ($user->dirtyAttributes) {
            if ($user->save()) {
                return [
                    'status' => 200,
                    'errors' => NULL,
                ];
            } else {
                return [
                    'status' => 69,
                    'errors' => $user->firstErrors,
                ];
            }
        }
        return NULL;
    }


    /**
     * @return array
     * @throws Exception
     */
    public function actionChangePassword()
    {
        $post = Yii::$app->request->post();
        $form = new ChangePasswordForm();
        $form->setAttributes($post);
        if ($form->setPass()) {
            return [
                'status' => 200,
                'message' => Yii::t('common', 'Mật khẩu đã được thay đổi.'),
                'errors' => NULL,
            ];
        }
        return [
            'status' => ExitCode::UNAVAILABLE,
            'errors' => $form->firstErrors,
        ];
    }

    public function actionUpdateProfile()
    {
        $post = Yii::$app->request->post();
        $form = new Profile();
        $form->setAttributes($post);
        if ($form->save()) {
            return [
                'status' => 200,
                'message' => Yii::t('common', 'Thông tin đã được cập nhật.'),
                'errors' => NULL,
            ];
        }
        return [
            'status' => ExitCode::UNAVAILABLE,
            'errors' => $form->firstErrors,
        ];
    }
}