<?php

namespace api\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class LoginForm
 * @package api\models
 */
class LoginForm extends Model
{
    public $username;
    public $password;

    private $_user;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required','message'=>'{attribute} không được để trống.'],
            [['username'], 'validateUsername'],
            ['password', 'validatePassword'],
        ];
    }

    public function validateUsername($attribute, $params, $validator)
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, Yii::t('common', 'Tên đăng nhập hoặc số điện thoại không hợp lệ.'));
        }
    }

    public function validatePassword($attribute, $params, $validator)
    {
        $user = $this->getUser();
        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError($attribute, 'Sai mật khẩu.');
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common', 'Tên đăng nhập'),
            'password' => Yii::t('common', 'Mật khẩu'),
        ];
    }

    /**
     * @return Customer|array|ActiveRecord|null
     */
    public function login()
    {
        if (!$this->validate()) {
            return NULL;
        }
        return $this->getUser();
    }

    /**
     * @return Customer|array|ActiveRecord|null
     */
    protected function getUser()
    {
        if ($this->_user === NULL) {
            $this->_user = Customer::find()->andWhere(
                [
                    'OR',
                    ['email' => $this->username],
                    ['phone' => $this->username],
                ])->one();
        }

        return $this->_user;
    }
}
