<?php

namespace backend\models;

use common\models\coupons\CouponModel;
use yii\base\Model;

class DynamicOrderCoupon extends Model
{
    /**
     * This is value of fee
     */
    public $coupon_value;

    /**
     * @var CouponModel
     */
    public $coupon;
}