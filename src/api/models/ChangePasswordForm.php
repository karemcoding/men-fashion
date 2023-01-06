<?php

namespace api\models;

use common\util\mailer\Alert;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class ChangePasswordForm
 * @package api\models
 */
class ChangePasswordForm extends Model
{
    public $old_password;
    public $password;
    public $confirm_password;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['old_password', 'password', 'confirm_password'], 'required'],
            [['password', 'confirm_password'], 'string', 'min' => 6],
            [['password', 'confirm_password'], 'required'],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password'],
            [['old_password'], 'validateOldPassword'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'old_password' => Yii::t('common', 'Mật khẩu cũ'),
            'password' => Yii::t('common', 'Mật khẩu'),
            'confirm_password' => Yii::t('common', 'Xác nhận mật khẩu'),
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateOldPassword($attribute, $params)
    {
        /** @var \common\models\Customer $model */
        $model = Yii::$app->user->identity;
        if (!$this->hasErrors()) {
            if (!$model->validatePassword($this->old_password)) {
                $this->addError($attribute, Yii::t('common', "Mật khẩu cũ không đúng."));
            }
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function setPass()
    {
        if (!$this->validate()) {
            return FALSE;
        }
        /** @var \common\models\Customer $model */
        $model = Yii::$app->user->identity;
        $model->generateAuthKey();
        $model->setPassword($this->password);
        $model->password_reset_token = NULL;
        if ($model->save(FALSE)) {
            Alert::changePassword($model);
            return TRUE;
        }
        return FALSE;
    }
}