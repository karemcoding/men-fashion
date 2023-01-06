<?php

namespace common\models;

use api\models\Stripe;
use common\behaviors\orderhistory\OrderHistoryBehavior;
use common\models\coupons\CouponModel;
use common\models\coupons\OrderCoupon;
use common\util\AppHelper;
use common\util\PayPalHelper;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;
use Throwable;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id
 * @property string|null $number
 * @property int $customer_id
 * @property string|null $receiver
 * @property string|null $receiver_tel
 * @property string|null $delivery_address
 * @property int|null $delivery_date
 * @property int|null $express_company_id
 * @property float|null $subtotal
 * @property float|null $total
 * @property int|null $status
 * @property int|null $payment_status
 * @property int|null $payment_method
 * @property string|null $payment_ref_id
 * @property string|null $payment_payer_ref_id
 * @property string|null $remark
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Customer $customer
 * @property-read OrderDetail[] $details
 * @property-read string $displayPaymentMethod
 * @property-read void|string $displayPaymentStatus
 * @property-read void|string $displayStatus
 * @property-read Product[] $products
 * @property-read OrderFee[] $orderFees
 * @property-read Fee[] $fees
 * @property-read OrderCoupon[] $orderCoupons
 * @property-read OrderHistory[] $audit
 * @property-read string $displayCustomer
 */
class Order extends ActiveRecord
{

    public static $alias = 'order';

    const METHOD_PAYPAL = 10;
    const METHOD_CARD = 20;
    const METHOD_CASH = 30;
    const METHOD_BANK = 40;

    const APP_METHOD_PAY_PAL = "PAYPAL";
    const APP_METHOD_CREDIT_CARD = "CREDIT_CARD";
    const APP_METHOD_STORE_PAYMENT = "STORE_PAYMENT";

    const DELIVERY_PICK_FROM_STORE = "PICK_UP";
    const DELIVERY_YOUR_DOOR = "YOUR_DOOR";

    const PAYMENT_UNPAID = -10;
    const PAYMENT_PAID = 10;
    const CREATED = 30;
    const DELIVERY = 20;
    const DONE = 10;
    const CANCEL = -10;
    const ROLLBACK = -20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::class,
            'audit' => [
                'class' => OrderHistoryBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            [['customer_id', 'status',
                'payment_status', 'payment_method',
                'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['total', 'subtotal'], 'number'],
            [['number', 'delivery_address',
                'payment_ref_id', 'remark'], 'string', 'max' => 255],
            [['number'], 'unique'],
            [['customer_id'], 'exist',
                'skipOnError' => TRUE,
                'targetClass' => Customer::class,
                'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã'),
            'number' => Yii::t('common', 'Số'),
            'customer_id' => Yii::t('common', 'Mã khách hàng'),
            'delivery_address' => Yii::t('common', 'Địa chỉ nhận hàng'),
            'subtotal' => Yii::t('common', 'Subtotal'),
            'total' => Yii::t('common', 'Tổng cộng'),
            'status' => Yii::t('common', 'Trạng thái'),
            'payment_status' => Yii::t('common', 'Trạng thái thanh toán'),
            'payment_method' => Yii::t('common', 'Phương thức thanh toán'),
            // 'payment_ref_id' => Yii::t('common', 'Payment Reference ID'),
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
     * @return ActiveQuery
     */
    public function getDetails()
    {
        return $this->hasMany(OrderDetail::class, ['order_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])->via('details');
    }

    public function getOrderFees()
    {
        return $this->hasMany(OrderFee::class, ['order_id' => 'id']);
    }

    public function getFees()
    {
        return $this->hasMany(Fee::class, ['id' => 'fee_id'])->via('orderFees');
    }

    /**
     * @return ActiveQuery
     */
    public function getOrderCoupons()
    {
        return $this->hasMany(OrderCoupon::class, ['order_id' => 'id']);
    }

    /**
     * @param Cart[] $carts
     * @param null $address
     * @param null $delivery
     * @param null $appMethod
     * @return array|Order|\yii\db\ActiveRecord|null
     */
    public static function add($carts = [], $address = NULL, $delivery = NULL, $appMethod = NULL, $fee = NULL, $coupon = NULL)
    {
        if ($delivery == self::DELIVERY_PICK_FROM_STORE) {
            $address = NULL;
        } elseif ($delivery == self::DELIVERY_YOUR_DOOR && $address == NULL) {
            return NULL;
        }
        $method = self::METHOD_CASH;
        if ($appMethod == self::APP_METHOD_PAY_PAL) {
            $method = self::METHOD_PAYPAL;
        }
        if ($appMethod == self::APP_METHOD_CREDIT_CARD) {
            $method = self::METHOD_CARD;
        }
        if ($appMethod == self::APP_METHOD_STORE_PAYMENT) {
            $method = self::METHOD_CASH;
        }

        $customerId = Yii::$app->user->identity->id;
        $order = new Order([
            'number' => uniqid(),
            'customer_id' => $customerId,
            'delivery_address' => $address,
            'status' => Order::CREATED,
            'payment_method' => $method,
            'payment_status' => self::PAYMENT_UNPAID,
        ]);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($order->save()) {
                $orderDetails = [];
                $cartDelete = [];
                $totalAmount = 0;
                foreach ($carts as $cart) {
                    $quantity = $cart->quantity;
                    $price = $cart->product->orderPrice();
                    $cartDelete[] = $cart->customer_id;
                    $amount = $quantity * $price;
                    $totalAmount = $totalAmount + $amount;
                    $orderDetails[] = new OrderDetail([
                        'order_id' => $order->id,
                        'product_id' => $cart->product_id,
                        'quantity' => $quantity,
                        'unit_price' => $price,
                        'amount' => $amount,
                    ]);
                }
                $order->updateTotal($totalAmount, $order->id, $fee, $coupon);
                OrderDetail::add($orderDetails);
                Cart::deleteAll(['customer_id' => $cartDelete]);
            }
            $transaction->commit();
            return self::find()->andWhere(['id' => $order->id])->one();
        } catch (Exception $e) {
            $transaction->rollBack();
        } catch (Throwable $e) {
            $transaction->rollBack();
        }
        return NULL;
    }

    /**
     * @param $moneyOfAllProduct
     * @return bool
     */
    public function updateTotal($moneyOfAllProduct, $orderId, $fee = NULL, $coupon = NULL)
    {
        $this->subtotal = $moneyOfAllProduct;
        $feeValue = 0;
        $couponValue = 0;
        if ($fee != NULL) {
            $feeObj = Fee::findOne(['id' => $fee]);
            $feeValue = $feeObj->value ?? 0;
            if ($feeObj != NULL) {
                $orderFee = new OrderFee([
                    'order_id' => $orderId,
                    'fee_id' => $feeObj->id,
                    'order_subtotal' => $moneyOfAllProduct,
                    'fee_value' => $feeObj->value,
                ]);
                $orderFee->save(FALSE);
            }
        }
        if ($coupon != NULL) {
            $couponObj = self::findCoupon($coupon);
            $couponValue = $couponObj->autoConvert()->value() ?? 0;
            if ($couponObj != NULL) {
                $orderCoupon = new OrderCoupon([
                    'order_id' => $orderId,
                    'coupon_id' => $couponObj->id,
                    'coupon_value' => $couponValue,
                ]);
                $orderCoupon->save(FALSE);
            }
        }
        $this->total = ($moneyOfAllProduct - $couponValue) + $feeValue;
        return $this->save();
    }

    /**
     * @param string $currency
     * @return null
     */
    public function paypal($currency = 'HKD')
    {
        $client = PayPalHelper::credentials();
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "reference_id" => $this->number,
                    "amount" => [
                        "value" => $this->total,
                        "currency_code" => $currency,
                    ],
                ],
            ],
            "application_context" => [
                "cancel_url" => AppHelper::webHostRoot() . '/api/payment/fail',
                "return_url" => AppHelper::webHostRoot() . '/api/payment/confirm-paypal',
            ],
        ];
        if ($client->environment->merchantId) {
            $request->body['purchase_units'][0]['payee'] = ['merchant_id' => $client->environment->merchantId];
        }
        try {
            $response = $client->execute($request);
            if ($response->statusCode >= 201 && $response->statusCode < 300) {
                foreach ($response->result->links as $item) {
                    if ($item->rel == 'approve') {
                        return $item->href;
                    }
                }
            }
        } catch (HttpException $ex) {
            return NULL;
        }
        return NULL;
    }

    /**
     * @param $number
     * @param $refOderId
     * @param $payerId
     * @return bool
     */
    public static function updateWithPaypal($number, $refOderId, $payerId)
    {
        if ($order = self::findOne(['number' => $number])) {
            $order->payment_payer_ref_id = $payerId;
            $order->payment_ref_id = $refOderId;
            $order->payment_status = self::PAYMENT_PAID;
            return $order->save();
        }
        return FALSE;
    }

    /**
     * @return string
     */
    public function cash()
    {
        return AppHelper::webHostRoot() . '/api/payment/success';
    }

    /**
     * @return mixed|string|null
     */
    public function creditCard()
    {
        $stripe = new Stripe();
        return $stripe->stripeCharge($this);
    }

    /**
     * @return string
     */
    public function getDisplayPaymentMethod()
    {
        return self::generatePaymentMethodView()[$this->payment_method];
    }

    /**
     * @return array
     */
    public static function generatePaymentMethodView()
    {
        $view = function ($title, $class) {
            return Html::tag('span',
                Yii::t('common', Yii::t('common', $title)),
                ['class' => "badge badge-soft-$class"]);
        };
        $result[self::METHOD_CASH] = $view('THANH TOÁN KHI NHẬN HÀNG', 'dark');
        $result[self::METHOD_CARD] = $view('CREDIT CARD', 'info');
        $result[self::METHOD_PAYPAL] = $view('PAYPAL', 'primary');
        $result[self::METHOD_BANK] = $view('CHUYỂN KHOẢN', 'success');
        return $result;
    }

    /**
     * @return array
     */
    public static function paymentMethodList()
    {
        $result[self::METHOD_CASH] = 'TIỀN MẶT';
        $result[self::METHOD_CARD] = 'CREDIT CARD';
        $result[self::METHOD_PAYPAL] = 'PAYPAL';
        $result[self::METHOD_BANK] = 'CHUYỂN KHOẢN';
        return $result;
    }

    /**
     * @return array
     */
    public static function paymentStatusList()
    {
        $result[self::PAYMENT_PAID] = 'ĐÃ THANH TOÁN';
        $result[self::PAYMENT_UNPAID] = 'CHƯA THANH TOÁN';
        return $result;
    }

    /**
     * @return string|void
     */
    public function getDisplayPaymentStatus()
    {
        if ($this->payment_status == self::PAYMENT_PAID) {
            return Html::tag('span', Yii::t('common', Yii::t('common', 'ĐÃ THANH TOÁN')),
                ['class' => 'badge bg-success text-white']);
        }
        if ($this->payment_status == self::PAYMENT_UNPAID) {
            return Html::tag('span', Yii::t('common', Yii::t('common', 'CHƯA THANH TOÁN')),
                ['class' => 'badge bg-warning text-white']);
        }
        return Html::tag('span', Yii::t('common', Yii::t('common', 'LỖI')),
            ['class' => 'badge bg-danger text-white']);
    }

    /**
     * @return array
     */
    public function getDisplayStatus()
    {
        return self::generateStatusView()[$this->status];
    }

    /**
     * @return string
     */
    public function getDisplayCustomer()
    {
        return Html::a($this->customer->name, ['customer/update', 'id' => $this->customer_id]);
    }

    /**
     * @param Order $order
     * @return array
     */
    public static function statusForSelect(self $order)
    {
        $status = [self::CREATED, self::DELIVERY, self::DONE, self::CANCEL, self::ROLLBACK];
        foreach ($status as $item) {
            $result[] = [
                'id' => $item,
                'text' => self::generateStatusView()[$item],
                'html' => self::generateStatusView()[$item],
                'selected' => $order->status == $item,
            ];
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function generateStatusView()
    {
        $view = function ($title, $class) {
            return Html::tag('span', Yii::t('common',
                Yii::t('common', "{title}", ['title' => $title])),
                ['class' => "badge bg-$class text-white"]);
        };
        $result[self::ROLLBACK] = $view('HOÀN TRẢ', 'dark');
        $result[self::CANCEL] = $view('HỦY', 'danger');
        $result[self::DONE] = $view('HOÀN THÀNH', 'success');
        $result[self::DELIVERY] = $view('ĐANG VẬN CHUYỂN', 'info');
        $result[self::CREATED] = $view('MỚI', 'primary');
        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getAudit()
    {
        return $this->hasMany(OrderHistory::class, ['record_pk' => 'id']);
    }

    /**
     * @param $value
     * @return array
     */
    public static function paymentStatusForSelect($value)
    {
        $status = [self::PAYMENT_UNPAID, self::PAYMENT_PAID];
        $title = function ($value) {
            if ($value == self::PAYMENT_PAID) {
                return Yii::t('common', 'ĐÃ THANH TOÁN');
            }
            return Yii::t('common', 'CHƯA THANH TOÁN');
        };
        foreach ($status as $item) {
            $result[] = [
                'id' => $item,
                'text' => self::generatePaymentStatusView($item),
                'html' => self::generatePaymentStatusView($item),
                'selected' => $value == $item,
                'title' => $title($item),
            ];
        }
        return $result;
    }

    /**
     * @param $status
     * @return string
     */
    public static function generatePaymentStatusView($status)
    {
        if ($status == self::PAYMENT_PAID) {
            return Html::tag('span',
                Yii::t('common', Yii::t('common', 'ĐÃ THANH TOÁN')),
                [
                    'class' => 'badge bg-success text-white',
                    'title' => Yii::t('common', Yii::t('common', 'ĐÃ THANH TOÁN')),
                ]);
        }
        if ($status == self::PAYMENT_UNPAID) {
            return Html::tag('span', Yii::t('common', Yii::t('common', 'CHƯA THANH TOÁN')),
                [
                    'class' => 'badge bg-warning text-white',
                    'title' => Yii::t('common', Yii::t('common', 'CHƯA THANH TOÁN')),
                ]);
        }
        return Html::tag('span', Yii::t('common', Yii::t('common', 'LỖI')),
            [
                'class' => 'badge bg-danger text-white',
                'title' => Yii::t('common', Yii::t('common', 'LỖI')),
            ]);
    }

    /**
     * @param $value
     * @return array
     */
    public static function paymentMethodForSelect($value)
    {
        $status = [self::METHOD_CASH, self::METHOD_BANK];
        $title = function ($value) {
            if ($value == self::METHOD_CASH) {
                return Yii::t('common', 'THANH TOÁN KHI NHẬN HÀNG');
            }
            return Yii::t('common', 'CHUYỂN KHOẢN');
        };
        foreach ($status as $item) {
            $result[] = [
                'id' => $item,
                'text' => self::generatePaymentMethodView()[$item],
                'html' => self::generatePaymentMethodView()[$item],
                'selected' => $value == $item,
                'title' => $title($item),
            ];
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function saveAsStatus(): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->status == Order::DONE) {
                foreach ($this->details as $orderDetail) {
                    $product = $orderDetail->product;
                    $product->sold = $product->sold + $orderDetail->quantity;
                    $product->save(FALSE);
                }
                $this->save(FALSE);
            } else {
                $this->save(FALSE);
            }
            $transaction->commit();
            return TRUE;
        } catch (\Exception $exception) {
            $transaction->rollBack();
            return FALSE;
        }
    }

    /**
     * @param $carts
     * @param null $fee
     * @param null $coupon
     * @return float|int
     */
    public static function cal($carts, $fee = NULL, $coupon = NULL)
    {
        $subtotalAmount = 0;
        $feeValue = 0;
        $couponValue = 0;
        if ($fee != NULL) {
            $feeValue = Fee::findOne(['id' => $fee])->value;
        }
        foreach ($carts as $cart) {
            $quantity = $cart->quantity;
            $price = $cart->product->orderPrice();
            $cartDelete[] = $cart->customer_id;
            $amount = $quantity * $price;
            $subtotalAmount = $subtotalAmount + $amount;
        }
        if ($coupon != NULL) {
            $couponObj = self::findCoupon($coupon);
            $couponValue = $couponObj->autoConvert()->value();
        }
        if ($coupon != NULL) {
            $couponObj = self::findCoupon($coupon);
            $couponValue = $couponObj->autoConvert()->value();
        }
        return ($subtotalAmount - $couponValue) + $feeValue;
    }

    /**
     * @param $id
     * @return array|CouponModel|\yii\db\ActiveRecord|null
     */
    public static function findCoupon($id)
    {
        return CouponModel::find()
            ->andWhere([CouponModel::$alias . '.id' => $id])
            ->one();
    }
}
