<?php

namespace common\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * This is the model class for table "{{%order_detail}}".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property float|null $quantity
 * @property float|null $unit_price
 * @property float|null $amount
 * @property int $product_discount_id
 *
 * @property Order $order
 * @property Product $product
 */
class OrderDetail extends ActiveRecord
{
    public static $alias = 'orderDetail';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id'], 'required'],
            [['order_id', 'product_id', 'product_discount_id'], 'integer'],
            [['quantity', 'unit_price', 'amount'], 'number'],
            [['order_id'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => Order::class,
                'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => TRUE,
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
            'id' => Yii::t('common', 'Mã'),
            'order_id' => Yii::t('common', 'Mã đơn hàng'),
            'product_id' => Yii::t('common', 'Mã sản phẩm'),
            'quantity' => Yii::t('common', 'Số lượng'),
            'unit_price' => Yii::t('common', 'Đơn giá'),
            'amount' => Yii::t('common', 'Tổng cộng'),
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
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
     * @param array $data
     * @return int
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function add($data = [])
    {
        $attributes = self::getTableSchema()->getColumnNames();

        if (self::validateMultiple($data, $attributes)) {
            return Yii::$app->db->createCommand()
                ->batchInsert(self::tableName(), $attributes, $data)->execute();
        }
        throw new Exception('Order detail invalid');
    }
}
