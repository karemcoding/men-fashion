<?php

/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 11:23 PM 5/10/2021
 * @projectName baseProject by ANDY
 */

namespace api\controllers;

use api\models\Cart;
use api\models\Product;
use common\models\Customer;
use common\models\Order;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Json;

/**
 * Class CartController
 * @package api\controllers
 */
class CartController extends ActiveController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbFilter']['actions'] = [
            'get' => ['GET'],
            '*' => ['POST'],
        ];
        $behaviors['authenticator'] = ['class' => HttpBearerAuth::class];
        return $behaviors;
    }

    /**
     * @return Product[]|array|ActiveRecord[]
     */
    public function actionGet()
    {
        return Product::findWithCustomerCart(Yii::$app->user->identity->id);
    }
    /**
     * @return array|Customer[]|ActiveRecord[]
     */
    public function actionAdd()
    {
        $productId = $this->request->post('product_id');
        $quantity = $this->request->post('quantity');
        $customerId = Yii::$app->user->identity->id;
        $cart = new Cart([
            'product_id' => $productId,
            'customer_id' => $customerId,
            'quantity' => $quantity,
            'created_at' => time(),
        ]);
        $cart->add();
        return Product::findWithCustomerCart($customerId);
    }

    /**
     * @return Product[]|array|ActiveRecord[]
     * @throws Throwable
     */
    public function actionRemove()
    {
        $productId = $this->request->post('product_id');
        $customerId = Yii::$app->user->identity->id;
        $cart = Cart::findOne([
            'product_id' => $productId,
            'customer_id' => $customerId,
        ]);
        if ($cart) {
            try {
                $cart->delete();
            } catch (Exception $exception) {
            }
        }
        return Product::findWithCustomerCart($customerId);
    }

    /**
     * @return Product[]|array|ActiveRecord[]
     */
    public function actionMultipleRemove()
    {
        $productIds = $this->request->post('product_ids');
        $customerId = Yii::$app->user->identity->id;
        if (!is_array($productIds)) {
            try {
                $productIds = Json::decode($productIds);
            } catch (Exception $exception) {
            }
        }
        Cart::deleteAll(['AND', ['product_id' => $productIds], 'customer_id' => $customerId]);
        return Product::findWithCustomerCart($customerId);
    }

    /**
     * @return Product[]|array|ActiveRecord[]
     */
    public function actionMultiAdd()
    {
        $products = $this->request->post('data');
        $customerId = Yii::$app->user->identity->id;
        try {
            $cart = [];
            $products = Json::decode($products);
            foreach ($products as $product) {
                $cart[] = new Cart([
                    'product_id' => $product['product_id'],
                    'customer_id' => $customerId,
                    'quantity' => $product['quantity'],
                    'created_at' => time(),
                ]);
            }
            Cart::multipleAdd($cart);
        } catch (Exception $exception) {
        }
        return Product::findWithCustomerCart($customerId);
    }

    /**
     * @return array
     */
    public function actionCal()
    {
        $fee = $this->request->post('fee') ?? NULL;
        $coupon = $this->request->post('coupon') ?? NULL;
        $customerId = Yii::$app->user->identity->id;
        $carts = Cart::find()->alias('cart')
            ->andWhere(['customer_id' => $customerId])
            ->joinWith(['product product'])->all();
        return [
            'amount' => Order::cal($carts, $fee, $coupon),
        ];
    }
}
