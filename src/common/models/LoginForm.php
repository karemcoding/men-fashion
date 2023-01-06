<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 *
 * @property-read null|User $user
 */
class LoginForm extends Model
{

    public $username;
    public $password;
    public $rememberMe = TRUE;
    public $captcha;

    protected $_user;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common', 'Tên đăng nhập'),
            'password' => Yii::t('common', 'Mật khẩu'),
            'rememberMe' => Yii::t('common', 'Ghi nhớ'),
            'captcha' => Yii::t('common', 'Xác thực'),
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return FALSE;
    }

    /**
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === NULL) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
