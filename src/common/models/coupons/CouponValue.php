<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 11:29 PM 6/20/2021
 * @projectName baseProject by ANDY
 */

namespace common\models\coupons;

interface CouponValue
{
    public function value($order = NULL);

    public static function type();

    public function couponValidate($order): bool;
}