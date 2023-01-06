<?php

namespace backend\models;

use common\models\Fee;
use yii\base\Model;

class DynamicOrderFee extends Model
{
    /**
     * This is subtotal of order
     */
    public $order_subtotal;

    /**
     * This is value of fee
     */
    public $fee_value;

    /**
     * @var Fee
     */
    public $fee;
}