<?php

namespace common\models;

use common\util\Status;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\web\IdentityInterface;

/**
 * @property int $id
 * @property int|null $group_id
 * @property string|null $username
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $name
 * @property string|null $address
 * @property string|null $avatar
 * @property string|null $credit_card_ref
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $verification_token
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Cart[] $carts
 * @property Favorite[] $favorites
 * @property Feedback[] $feedbacks
 * @property-write mixed $password
 * @property-read string $authKey
 * @property-read Product[] $productsMapCart
 * @property-read Product[] $productsMapFavorite
 * @property-read CustomerGroup $group
 * @property Order[] $orders
 */
class Customer extends ActiveRecord implements IdentityInterface
{
    public static $alias = 'customer';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::class,
            'blameable' => BlameableBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_key', 'password_hash'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [
                [
                    'username', 'email', 'phone', 'password_hash',
                    'password_reset_token', 'verification_token', 'address'
                ],
                'string', 'max' => 255
            ],
            [['auth_key'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 50],
            [['username', 'email', 'phone'], 'validateDuplicate'],
            [['password_reset_token'], 'unique'],
            [['verification_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'username' => Yii::t('common', 'Tên đăng nhập'),
            'email' => Yii::t('common', 'Email'),
            'phone' => Yii::t('common', 'Điện thoại'),
            'auth_key' => Yii::t('common', 'Auth Key'),
            'password_hash' => Yii::t('common', 'Mật khẩu'),
            'password_reset_token' => Yii::t('common', 'Password Reset Token'),
            'verification_token' => Yii::t('common', 'Verification Token'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'name' => Yii::t('common', 'Tên')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(CustomerGroup::class, ['id' => 'group_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['customer_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getProductsMapCart()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])
            ->viaTable('{{%cart}}', ['customer_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['customer_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getProductsMapFavorite()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])
            ->viaTable('{{%favorite}}', ['customer_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::class, ['customer_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['customer_id' => 'id']);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return static::find()->alias('this')
            ->andWhere([
                'this.id' => $id,
                'this.status' => Status::STATUS_ACTIVE])
            ->one();
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $password
     *
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}
