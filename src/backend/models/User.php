<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class UserForm
 *
 * @package backend\models
 */
class User extends \common\models\User
{

    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';

    public $password;
    public $confirm_password;

    /**
     * @return array
     */
    public function rules()
    {
        $rules = [
            [['email', 'username'], 'trim'],
            ['email', 'email'],
            [['email', 'username'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 30],
            [['username', 'email', 'tel', 'role_id', 'name', 'auth_key', 'password_hash'], 'required'],
            ['status', 'in', 'range' => [parent::STATUS_ACTIVE, parent::STATUS_INACTIVE, parent::STATUS_DELETED]],
            [['password', 'confirm_password'], 'string', 'min' => 6],
            [['password', 'confirm_password'], 'required', 'on' => self::SCENARIO_CREATE],
            ['confirm_password', 'compare', 'compareAttribute' => 'password'],
            [['username', 'email', 'tel'], 'validateDuplicate'],
            [['password_reset_token'], 'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'password_reset_token',
            ],
            [['verification_token'], 'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'verification_token'
            ],
        ];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'role_id' => Yii::t('common', 'Vai trò'),
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenario[self::SCENARIO_UPDATE] = ['name', 'email', 'tel', 'status', 'password', 'confirm_password', 'role_id'];
        return ArrayHelper::merge(parent::scenarios(), $scenario);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function add()
    {
        $this->setPassword($this->password);
        $this->generateAuthKey();
        return $this->save();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function edit()
    {
        if ($this->password) {
            $this->setPassword($this->password);
            $this->generateAuthKey();
        }
        return $this->save();
    }
}
