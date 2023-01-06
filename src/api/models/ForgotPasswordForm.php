<?php

namespace api\models;

use common\util\mailer\Alert;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class ForgotPasswordForm
 * @package api\models
 */
class ForgotPasswordForm extends Model
{
    const SCENARIO_GET_CODE = 'GET_CODE';

    public $email;
    public $code;
    public $password;
    public $confirm_password;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'validateEmailExist'],
            [['code', 'password', 'confirm_password'], 'required', 'on' => [self::SCENARIO_DEFAULT]],
            [['password', 'confirm_password'], 'string', 'min' => 6],
            [['password', 'confirm_password'], 'required'],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function validateEmailExist($attribute, $params, $validator)
    {
        $model = Customer::find()
            ->andWhere([$attribute => $this->$attribute])
            ->active();
        if (!$model->exists()) {
            $this->addError($attribute, Yii::t('common', "\"{$this->$attribute}\" chưa tồn tại."));
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('common', 'Email'),
            'code' => Yii::t('common', 'Mã xấc nhận'),
            'password' => Yii::t('common', 'Mật khẩu'),
            'confirm_password' => Yii::t('common', 'Xác nhận mật khẩu'),
        ];
    }

    /**
     * @return array|array[]
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_GET_CODE] = ['email'];
        return $scenarios;
    }

    /**
     * @return bool
     */
    public function sendMail()
    {
        if (!$this->validate()) {
            return FALSE;
        }
        $model = Customer::find()
            ->andWhere(['email' => $this->email])
            ->active()->one();
        $generateCode = substr(md5(microtime()), rand(0, 26), 6);
        $model->password_reset_token = $generateCode . '/' . time() . '/' . 0;
        if ($model->save(FALSE)) {
            return Alert::validateCode($model, $generateCode);
        }
        return FALSE;
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
        $model = Customer::find()
            ->andWhere(['email' => $this->email])
            ->active()
            ->one();
        $code = explode('/', $model->password_reset_token);
        if (time() - $code[1] > 600 || $code[2] >= 3) {
            $this->addError('code', Yii::t('common', 'Mã xác nhận hết hạn'));
            return FALSE;
        }
        if ($code[0] != $this->code) {
            $code[2] = $code[2] + 1;
            $model->password_reset_token = implode('/', $code);
            $model->save(FALSE);
            $this->addError('code', Yii::t('common', 'Mã xác nhận không đúng'));
            return FALSE;
        }
        $model->generateAuthKey();
        $model->setPassword($this->password);
        $model->password_reset_token = NULL;
        return $model->save(FALSE);
    }
}