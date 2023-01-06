<?php

namespace common\models;

use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "{{%favorite}}".
 *
 * @property int $product_id
 * @property int $customer_id
 * @property int|null $created_at
 *
 * @property Customer $customer
 * @property Product $product
 */
class Favorite extends ActiveRecord
{

    public function behaviors()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%favorite}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'customer_id'], 'required'],
            [['product_id', 'customer_id', 'created_at'], 'integer'],
            [['product_id', 'customer_id'], 'unique', 'targetAttribute' => ['product_id', 'customer_id']],
            [['customer_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'customer_id' => 'Customer ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCartOfFavorite()
    {
        return $this->hasOne(Cart::class, ['customer_id' => 'customer_id', 'product_id' => 'product_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function request()
    {
        $fObj = self::findOne([
            'customer_id' => $this->customer_id,
            'product_id' => $this->product_id,
        ]);
        if ($fObj) {
            $fObj->delete();
        } else {
            $this->save();
        }
        /** @var Customer $customer */
        $customer = Yii::$app->user->identity;
        return Product::findWithCustomerFavorite($customer->id);
    }

    /**
     * @param array $data
     * @return false|int
     * @throws InvalidConfigException
     * @throws Exception
     */
    public static function multipleAdd($data = [])
    {
        $attributes = self::getTableSchema()->getColumnNames();

        if (self::validateMultiple($data, $attributes)) {
            Favorite::deleteAll(['customer_id' => Yii::$app->user->identity->getId()]);
            return Yii::$app->db->createCommand()
                ->batchInsert(self::tableName(),
                    $attributes, $data)->execute();
        }
        return FALSE;
    }
}
