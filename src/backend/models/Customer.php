<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 8:36 PM 4/30/2021
 * @projectName baseProject by ANDY
 */

namespace backend\models;


use yii\base\Exception;

/**
 * Class Customer
 * @package backend\models
 */
class Customer extends \common\models\Customer
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
        return [
            [['name', 'phone', 'group_id'], 'required'],
            [['email', 'username', 'phone'], 'trim'],
            [['email'], 'email'],
            [['email', 'username', 'phone', 'name', 'address'], 'string', 'max' => 255],
            [['password', 'confirm_password'], 'string', 'min' => 6],
            [['password', 'confirm_password', 'email'], 'required', 'on' => self::SCENARIO_CREATE],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['username', 'phone', 'email'], 'validateDuplicate']
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE] = ['name', 'phone', 'password', 'confirm_password', 'status', 'address', 'avatar'];
        return $scenarios;
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
        if ($this->password != '') {
            $this->setPassword($this->password);
            $this->generateAuthKey();
        }
        return $this->save();
    }
}