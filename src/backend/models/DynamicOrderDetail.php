<?php


namespace backend\models;


use yii\base\Model;

class DynamicOrderDetail extends Model
{
    public $quantity;

    public $unit_price;

    public $amount;

    /**
     * @var Product
     */
    public $product;
}