<?php

namespace backend\models;

use common\models\coupons\CouponModel;
use common\models\coupons\OrderCoupon;
use common\models\ExpressCompany;
use common\models\Fee;
use common\models\Order;
use common\models\OrderDetail;
use common\models\OrderFee;
use DateTime;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-read array $firstSuccesses
 */
class DynamicOrder extends Model
{
    const ORDER_PRODUCT_LIST = 'DYNAMIC_ORDER_PRODUCT_LIST';

    const PRODUCT_QUANTITY = 'DYNAMIC_ORDER_PRODUCT_QUANTITY';

    const FEE_LIST = 'DYNAMIC_ORDER_FEE_IDS';

    const COUPON_LIST = 'DYNAMIC_ORDER_COUPON_IDS';

    const OBJECT = 'DYNAMIC_ORDER_OBJECT';

    public $receiver;

    public $receiver_tel;

    public $delivery_address;

    public $delivery_date;

    public $express_company_id;

    public $remark;

    public $payment_status;

    public $number;

    public $created_at;

    public $payment_method;

    public $subtotal;

    public $total;

    public $customer_id;

    public $customer;

    public $feeIds = [];

    public $productIds = [];

    public $couponIds = [];

    /**
     * Id sản phẩm và số lượng do người dùng post lên
     */
    public $productQuantity = [];

    /**
     * @var Customer
     */
    public $customer_obj;

    /**
     * @var Fee[]
     */
    public $fees = [];

    private $_successes = [];

    /**
     * @var DynamicOrderDetail[]
     */
    public $order_details;

    /**
     * @var DynamicOrderFee[]
     */
    public $order_fees = [];

    /**
     * @var Product[]
     */
    public $products = [];

    /**
     * @var CouponModel[]
     */
    public $coupons = [];

    /**
     * @var DynamicOrderCoupon[]
     */
    public $order_coupons = [];

    public $shipping_method;

    /**
     * @return array[]
     */
    public function rules()
    {
        return [
            [['customer_id', 'delivery_date',
                'customer_id', 'customer', 'receiver',
                'receiver_tel', 'shipping_method',
                'payment_status', 'payment_method'], 'required'],
            [['customer_id'], 'integer'],
            [['customer_id'], 'exist',
                'targetClass' => Customer::class,
                'targetAttribute' => 'id',
            ],
            [['delivery_address', 'remark', 'customer'], 'string', 'max' => 255],
            [['express_company_id'], 'exist',
                'targetClass' => ExpressCompany::class,
                'targetAttribute' => 'id',
            ],
            ['payment_status', 'in', 'range' => [Order::PAYMENT_PAID, Order::PAYMENT_UNPAID]],
            ['payment_method', 'in', 'range' => [Order::METHOD_CASH, Order::METHOD_BANK]],
            [
                ['express_company_id', 'delivery_address'], 'required',
                'when' => [$this, 'validateExpressCompany'],
                'whenClient' => "function (attribute, value) {
                    return $('#dynamicorder-shipping_method').val() == 'YOUR_DOOR';
                }",
            ],
        ];
    }

    /**
     * @param DynamicOrder $model
     * @param $attribute
     * @return bool
     */
    public function validateExpressCompany(DynamicOrder $model, $attribute): bool
    {
        return $model->shipping_method == Order::DELIVERY_YOUR_DOOR;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'customer' => Yii::t('common', 'Khách hàng'),
            'express_company_id' => Yii::t('common', 'Đơn vị vận chuyển'),
        ];
    }

    /**
     * @return bool
     */
    public function validateQuantity()
    {
        if (empty($this->productQuantity)) return FALSE;
        $valid = TRUE;
        foreach ($this->productQuantity as $key => $item) {
            $product = $this->products[$key];
            $inventory = $product->inventory ?? 0;
            if ($item > $inventory || $item == 0) {
                $this->addError("product[$key]", Yii::t('common', "Số lượng {0} không hợp lệ", [$product->name]));
                $valid = FALSE;
            } else {
                $this->addSuccess("product[$key]", Yii::t('common', 'Số lượng hợp lệ'));
            }
        }
        return $valid;
    }

    /**
     * @return bool
     */
    public function validateCoupon()
    {
        $errors = 0;
        foreach ($this->coupons as $coupon) {
            if (!$coupon->autoConvert()->couponValidate($this)) {
                $errors++;
                Yii::$app->session->addFlash('error', Yii::t('common',
                    'Can not apply coupon {0}', [$coupon->name]));
            }
        }
        return $errors == 0;
    }

    /**
     * @param $attribute
     * @param string $error
     */
    public function addSuccess($attribute, $error = '')
    {
        $this->_successes[$attribute][] = $error;
    }

    /**
     * @return array
     */
    public function getFirstSuccesses(): array
    {
        if (empty($this->_successes)) {
            return [];
        }

        $successes = [];
        foreach ($this->_successes as $name => $es) {
            if (!empty($es)) {
                $successes[$name] = reset($es);
            }
        }

        return $successes;
    }

    /**
     * @return array
     */
    public static function selectExpress()
    {
        $com = ExpressCompany::selectExpress();
        return ArrayHelper::merge([NULL => Yii::t('common', 'Mua tại cửa hàng')], $com);
    }

    /**
     * @return bool
     */
    public function saveSession()
    {
        if ($this->shipping_method == Order::DELIVERY_PICK_FROM_STORE) {
            $this->express_company_id = NULL;
            $this->delivery_address = NULL;
        }

        if (!$this->validate()) {
            return FALSE;
        }

        $this->customer_obj = Customer::findOne(['id' => $this->customer_id]);
        $this->number = uniqid();
        $this->created_at = time();
        $this->setOrder();
        return TRUE;
    }

    public function setOrder()
    {
        $temp = [];
        $subtotal = 0;
        foreach ($this->products as $product) {
            $price = $product->orderPrice();
            $amount = $price * $this->productQuantity[$product->id];
            $subtotal = $subtotal + $amount;
            $temp[] = new DynamicOrderDetail([
                'product' => $product,
                'quantity' => $this->productQuantity[$product->id],
                'unit_price' => $price,
                'amount' => $amount,
            ]);
        }
        $this->order_details = $temp;
        $this->subtotal = $subtotal;
        $this->applyFee($subtotal);
        $this->applyCoupon();
    }

    /**
     * @param $subtotal
     */
    public function applyFee($subtotal)
    {
        $total = $subtotal;
        $orderFees = [];
        foreach ($this->fees as $fee) {
            $value = $fee->value;
            if ($fee->type == Fee::TYPE_PERCENT) {
                $value = $subtotal * ($fee->value / 100);
            }
            $total = $total + $value;
            $orderFees[] = new DynamicOrderFee([
                'fee' => $fee,
                'order_subtotal' => $subtotal,
                'fee_value' => $value,
            ]);
        }
        $this->total = $total;
        $this->order_fees = $orderFees;
    }

    public function applyCoupon()
    {
        $orderCoupons = [];
        foreach ($this->coupons as $key => $coupon) {
            $couponValue = $coupon->autoConvert()->value($this);
            $orderCoupons[] = new DynamicOrderCoupon([
                'coupon' => $coupon,
                'coupon_value' => $couponValue,
            ]);
            $this->total = $this->total - $couponValue;
        }
        $this->order_coupons = $orderCoupons;
    }

    /**
     * @return bool
     */
    public function save()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $date = function ($dateString) {
                return DateTime::createFromFormat("d/m/Y", $dateString)->getTimestamp();
            };
            $order = new Order([
                'delivery_date' => $date($this->delivery_date),
                'status' => Order::CREATED,
                'created_at' => time(),
                'updated_at' => time(),
                'created_by' => Yii::$app->user->identity->id,
                'updated_by' => Yii::$app->user->identity->id,
            ]);
            $order->setAttributes($this->getAttributes(NULL, ['delivery_date']), FALSE);
            if ($order->save()) {
                /** Tính các sản phẩm */
                $orderDetails = [];
                foreach ($this->order_details as $orderDetail) {
                    $orderDetails[] = new OrderDetail([
                        'order_id' => $order->id,
                        'product_id' => $orderDetail->product->id,
                        'quantity' => $orderDetail->quantity,
                        'unit_price' => $orderDetail->unit_price,
                        'amount' => $orderDetail->amount,
                        'product_discount_id' => $orderDetail->product->discountObj->id ?? NULL,
                    ]);
                }
                /** Tính các loại phí */
                $oderFees = [];
                foreach ($this->order_fees as $orderFee) {
                    $oderFees[] = new OrderFee([
                        'order_id' => $order->id,
                        'fee_id' => $orderFee->fee->id,
                        'fee_value' => $orderFee->fee_value,
                        'order_subtotal' => $orderFee->order_subtotal,
                    ]);
                }
                /** Tính các coupon */
                $coupons = [];
                foreach ($this->order_coupons as $orderCoupon) {
                    $coupons[] = new OrderCoupon([
                        'order_id' => $order->id,
                        'coupon_id' => $orderCoupon->coupon->id,
                        'coupon_value' => $orderCoupon->coupon_value,
                    ]);
                }
                OrderDetail::add($orderDetails);
                OrderFee::add($oderFees);
                OrderCoupon::add($coupons);
                $transaction->commit();
                return TRUE;
            } elseif ($es = $order->firstErrors) {
                foreach ($es as $item) {
                    Yii::$app->session->addFlash('error', $item);
                }
                $transaction->rollBack();
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('error', "{$exception->getMessage()}");
        }
        return FALSE;
    }

    public static function removeSession()
    {
        Yii::$app->session->remove(DynamicOrder::ORDER_PRODUCT_LIST);
        Yii::$app->session->remove(DynamicOrder::PRODUCT_QUANTITY);
        Yii::$app->session->remove(DynamicOrder::FEE_LIST);
        Yii::$app->session->remove(DynamicOrder::COUPON_LIST);
        Yii::$app->session->remove(DynamicOrder::OBJECT);
    }

    public static function selectShippingMethod()
    {
        return [
            Order::DELIVERY_PICK_FROM_STORE => Yii::t('common', 'Mua tại cửa hàng'),
            Order::DELIVERY_YOUR_DOOR => Yii::t('common', 'Giao hàng'),
        ];
    }
}