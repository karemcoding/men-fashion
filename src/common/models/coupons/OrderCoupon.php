<?php

namespace common\models\coupons;

use common\models\ActiveRecord;
use common\models\Order;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * This is the model class for table "{{%order_coupon}}".
 *
 * @property int|null $order_id
 * @property int|null $coupon_id
 * @property float|null $coupon_value
 *
 * @property CouponModel $coupon
 * @property Order $order
 */
class OrderCoupon extends ActiveRecord
{
    public static $alias = 'orderCoupon';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_coupon}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'coupon_id'], 'integer'],
            [['coupon_value'], 'number'],
            [['coupon_id'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => CouponModel::class,
                'targetAttribute' => ['coupon_id' => 'id'],
            ],
            [['order_id'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => Order::class,
                'targetAttribute' => ['order_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('common', 'Order ID'),
            'coupon_id' => Yii::t('common', 'Coupon ID'),
            'coupon_value' => Yii::t('common', 'Coupon Value'),
        ];
    }

    /**
     * Gets query for [[Coupon]].
     *
     * @return ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(CouponModel::class, ['id' => 'coupon_id']);
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
