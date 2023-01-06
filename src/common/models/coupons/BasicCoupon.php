<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 11:17 PM 6/19/2021
 * @projectName baseProject by ANDY
 */

namespace common\models\coupons;

use backend\models\DynamicOrder;
use Yii;

/**
 * Class BasicCoupon
 * @package common\models\coupons
 */
class BasicCoupon extends CouponModel implements CouponValue
{
    public $name;
    public $value;
    public $from;
    public $to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'name'], 'required'],
            [['name'], 'string'],
            [['value'], 'number'],
            [['from', 'to', 'description'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'value' => Yii::t('common', 'Value'),
            'name' => Yii::t('common', 'Name'),
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
        return $this->value;
    }

    /**
     * @return string
     */
    public static function type()
    {
        return 'coupon';
    }

    /**
     * @param DynamicOrder $order
     * @return bool
     */
    public function couponValidate($order): bool
    {
        return TRUE;
    }
}