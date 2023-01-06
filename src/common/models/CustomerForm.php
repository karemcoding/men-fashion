<?php

namespace common\models;

use yii\base\Exception;
use yii\base\Model;
use Yii;
/**
 * Class CustomerForm
 * @package backend\models
 *
 * @property-read bool $isNewRecord
 */
class CustomerForm extends Model
{

    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';

    const SCENARIO_SIGNUP_EXTERNAL = 'signup_external';

    public $id;
    public $username;
    public $email;
    public $phone;
    public $password;
    public $confirm_password;
    public $status;
    public $name;

    /**
     * @return array
     */
    public function rules()
    {
        $rules = [
            [['email', 'username', 'phone'], 'trim','message'=>'{attribute} không hợp lệ.'],
            [['email'], 'email','message'=>'{attribute} không hợp lệ.'],
            [['email'], 'string', 'max' => 255],
            [['username', 'phone', 'name'], 'string','message'=>'{attribute} không hợp lệ.'],
            [['email'], 'required','message'=>'{attribute} không được để trống.'],
            [['phone','name'], 'required', 'on' => self::SCENARIO_CREATE,'message'=>'{attribute} không được để trống.'],
            [
                'email',
                'unique',
                'targetClass' => Customer::class,
                'message' => 'Email đã đăng ký.',
                'on' => self::SCENARIO_CREATE
            ],
            [
                'username',
                'unique',
                'targetClass' => Customer::class,
                'message' => 'Tên đăng nhập đã tồn tại.',
                'on' => self::SCENARIO_CREATE
            ],
            [
                'phone',
                'unique',
                'targetClass' => Customer::class,
                'message' => 'Số điện thoại đã đăng ký.',
                'on' => self::SCENARIO_CREATE
            ],
            [['password', 'confirm_password'], 'string', 'min' => 6,'tooShort'=>'{attribute} phải có ít nhất 6 ký tự.'],
            [['password', 'confirm_password'], 'required', 'on' => self::SCENARIO_CREATE,'message'=>'{attribute} không được để trống.'],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password', 'message'=>'Xác nhận mật khẩu không trùng khớp.'],
            [
                'email',
                'validateDuplicate',
                'message' => 'Email đã đăng ký.',
                'on' => self::SCENARIO_UPDATE
            ],
            [
                'username',
                'validateDuplicate',
                'message' => 'Tên đăng nhập đã tồn tại.',
                'on' => self::SCENARIO_UPDATE
            ],
            [
                'phone',
                'validateDuplicate',
                'message' => 'Số điện thoại đã đăng ký.',
                'on' => self::SCENARIO_UPDATE
            ],
            ['status', 'integer']
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE] = ['name', 'phone', 'password', 'confirm_password', 'status'];
        $scenarios[self::SCENARIO_SIGNUP_EXTERNAL] = ['name', 'phone', 'email'];

        return $scenarios;
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     * @param $current
     */
    public function validateDuplicate($attribute, $params, $validator, $current)
    {
        $customer = Customer::find()
            ->andWhere([$attribute => $this->$attribute])
            ->andWhere(['<>', 'id', $this->id])
            ->exists();
        if ($customer) {
            $this->addError($attribute, $validator->message);
        }
    }

    /**
     * @return bool|null
     * @throws Exception
     */
    public function create()
    {
        if (!$this->validate()) {
            return NULL;
        }

        $customer = new Customer();
        $customer->name = $this->name;
        $customer->username = null;
        $customer->email = $this->email;
        $customer->phone = !empty($this->phone) ? $this->phone : null;
        $customer->status = $this->status;
        $customer->setPassword($this->password);
        $customer->generateAuthKey();
        return $customer->save();
    }

    /**
     * @return bool|null
     * @throws Exception
     */
    public function update()
    {
        if (!$this->validate()) {
            return NULL;
        }
        $customer = Customer::findOne($this->id);
        if ($customer) {
            $customer->username = null;
            $customer->name = $this->name;
            $customer->email = $this->email;
            $customer->phone = !empty($this->phone) ? $this->phone : null;
            $customer->status = $this->status;

            //if password not null then create password
            if (!empty($this->password)) {
                $customer->setPassword($this->password);
                $customer->generateAuthKey();
            }
            return $customer->save();
        }

        return NULL;
    }

    /**
     * @return bool
     */
    public function getIsNewRecord()
    {
        if (empty($this->id)) {
            return TRUE;
        }

        return FALSE;
    }
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('common', 'Số điện thoại'),
            'password' => Yii::t('common', 'Mật khẩu'),
            'name' => Yii::t('common', 'Tên'),
            'confirm_password' => Yii::t('common', 'Xác nhận mật khẩu'),
        ];
    }
}
