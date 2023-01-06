<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 11:17 PM 6/19/2021
 * @projectName baseProject by ANDY
 */

namespace common\models\coupons;

use backend\models\DynamicOrder;
use common\models\Fee;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class FreeShipCoupon
 * @package common\models\coupons
 */
class FreeOneFeeCoupon extends CouponModel implements CouponValue
{
    public $fee_id;
    public $minimum_order_amount;
    public $quantity;
    public $from;
    public $to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fee_id', 'minimum_order_amount', 'name'], 'required'],
            [['fee_id', 'quantity'], 'integer'],
            [['minimum_order_amount'], 'number'],
            [['from', 'to', 'description'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'fee_id' => Yii::t('common', 'Fee'),
            'minimum_order_amount' => Yii::t('common', 'Minimum Order Amount'),
            'status' => Yii::t('common', 'Trạng thái'),
            'from' => Yii::t('common', 'From'),
            'to' => Yii::t('common', 'To'),
        ];
    }

    /**
     * @param DynamicOrder $order
     * @return mixed
     */
    public function value($order = NULL)
    {
        foreach ($order->order_fees as $orderFee) {
            if ($this->fee_id == $orderFee->fee->id) {
                return $orderFee->fee_value;
            }
        }
        return 0;
    }

    /**
     * @return string
     */
    public static function type()
    {
        return 'free_one_fee';
    }

    /**
     * @return array
     */
    public static function feeList()
    {
        $fees = Fee::find()->active()->all();
        return ArrayHelper::map($fees, 'id', 'name');
    }

    /**
     * @param DynamicOrder $order
     * @return bool
     */
    public function couponValidate($order): bool
    {
        return in_array($this->fee_id, $order->feeIds);
    }
}