<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property int $id
 * @property int $product_id
 * @property int $customer_id
 * @property string|null $feedback
 * @property int|null $score
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Customer $customer
 * @property Product $product
 */
class Feedback extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'customer_id', 'feedback', 'score'], 'required'],
            [['product_id', 'customer_id', 'score', 'status',
                'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['feedback'], 'string', 'max' => 1000],
            [['score'], 'integer', 'max' => 5, 'min' => 1],
            [['customer_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Customer::class,
                'targetAttribute' => ['customer_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true,
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
            'id' => Yii::t('common', 'ID'),
            'product_id' => Yii::t('common', 'Product ID'),
            'customer_id' => Yii::t('common', 'Customer ID'),
            'feedback' => Yii::t('common', 'Feedback'),
            'score' => Yii::t('common', 'Score'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
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
