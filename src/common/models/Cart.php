<?php

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%cart}}".
 *
 * @property int $product_id
 * @property int $customer_id
 * @property float|null $quantity
 * @property int|null $created_at
 *
 * @property Customer $customer
 * @property Product $product
 */
class Cart extends ActiveRecord
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
        return '{{%cart}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'customer_id'], 'required'],
            [['product_id', 'customer_id', 'created_at'], 'integer'],
            [['quantity'], 'integer', 'min' => 1],
            [['product_id', 'customer_id'], 'unique',
                'targetAttribute' => ['product_id', 'customer_id']],
            [['customer_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => Customer::class,
                'targetAttribute' => ['customer_id' => 'id']],
            [['product_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => Product::class,
                'targetAttribute' => ['product_id' => 'id']],
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
            'quantity' => 'Quantity',
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
     * Gets query for [[Product]].
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
