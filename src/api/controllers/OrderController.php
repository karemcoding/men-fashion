<?php

namespace api\controllers;

use api\models\Cart;
use api\models\Customer;
use common\models\coupons\BasicCoupon;
use common\models\Fee;
use common\models\Order;
use common\util\Status;
use Yii;
use yii\console\ExitCode;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class OrderController
 * @package api\controllers
 */
class OrderController extends ActiveController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbFilter']['actions'] = [
            'add' => ['POST'],
        ];
        $behaviors['authenticator'] = ['class' => HttpBearerAuth::class];
        return $behaviors;
    }

    /**
     * @return array|null
     */
    public function actionAdd()
    {
        $address = $this->request->post('address');
        $delivery = $this->request->post('delivery');
        $method = $this->request->post('method');
        $receiver = $this->request->post('receiver');
        $receiver_tel = $this->request->post('receiver_tel');
        $remark = $this->request->post('remark');
        $fee = $this->request->post('fee') ?? NULL;
        $coupon = $this->request->post('coupon') ?? NULL;
        $customerId = Yii::$app->user->identity->id;
        $carts = Cart::find()->alias('cart')
            ->andWhere(['customer_id' => $customerId])
            ->joinWith(['product product'])->all();
        if (!$carts) return ['status' => ExitCode::DATAERR, 'link' => NULL];
        if ($order = Order::add($carts, $address, $delivery, $method, $fee, $coupon)) {
            $order->receiver = $receiver;
            $order->receiver_tel = $receiver_tel;
            $order->save();

            if ($order->payment_method == Order::METHOD_PAYPAL) {
                return ['status' => 200, 'link' => $order->paypal()];
            }
            if ($order->payment_method == Order::METHOD_CARD) {
                return ['status' => 200, 'link' => $order->creditCard()];
            }
            if ($order->payment_method == Order::METHOD_CASH) {
                return ['status' => 200, 'link' => $order->cash()];
            }
        }
        return ['status' => ExitCode::DATAERR, 'link' => NULL];
    }

    /**
     * @return Customer|Customer[]|array|ActiveRecord|ActiveRecord[]|null
     */
    public function actionGet()
    {
        /** @var Customer $user */
        $user = Yii::$app->user->identity;
        $query = $user->getOrders()->alias('this')
            ->joinWith(['details details' => function (ActiveQuery $q) {
                $q->joinWith(['product product' => function (ActiveQuery $q) {
                    $q->joinWith(['category category']);
                }]);
            }])
            ->orderBy(['this.id' => SORT_DESC])->asArray();
        if ($id = $this->request->get('id')) {
            return $query->andWhere(['this.id' => $id])->one();
        }
        return $query->all();
    }

    /**
     * @return array|null
     */
    public function actionGetShippingFee()
    {
        $fee = Fee::findOne(['shipping_fee' => Status::STATUS_ACTIVE]);
        if ($fee == NULL) return NULL;
        return [
            'id' => $fee->id,
            'name' => $fee->name,
            'value' => $fee->value,
        ];
    }

    /**
     * @return array
     */
    public function actionGetCoupon()
    {
        $all = BasicCoupon::find()
            ->joinWith(['couponProperties'])
            ->andWhere(['type' => BasicCoupon::type()])
            ->notDeleted()
            ->all();
        $result = [];
        foreach ($all as &$item) {
            $temp = [
                'id' => $item->id,
                'name' => $item->convert()->name,
                'value' => $item->convert()->value,
            ];
            $result[] = $temp;
        }
        return $result;
    }
}