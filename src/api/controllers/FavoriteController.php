<?php

namespace api\controllers;

use common\models\Customer;
use common\models\Favorite;
use common\models\Product;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Json;

/**
 * Class FavoriteController
 * @package api\controllers
 */
class FavoriteController extends ActiveController
{
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
     * @return array|\common\models\ActiveRecord[]|Product[]
     */
    public function actionIndex()
    {
        /** @var Customer $customer */
        $customer = Yii::$app->user->identity;
        return Product::findWithCustomerFavorite($customer->id);
    }

    /**
     * @return array|Product[]|ActiveRecord[]
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionRequest()
    {
        $productId = $productId = $this->request->post('product_id');
        $userId = Yii::$app->user->identity->id;
        $favoriteModel = new Favorite([
            'customer_id' => $userId,
            'product_id' => $productId,
            'created_at' => time(),
        ]);
        return $favoriteModel->request();
    }

    /**
     * @return array|\common\models\ActiveRecord[]|Product[]
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
        Favorite::deleteAll(['AND', ['product_id' => $productIds], 'customer_id' => $customerId]);
        return Product::findWithCustomerFavorite($customerId);
    }

    /**
     * @return array|\common\models\ActiveRecord[]|Product[]
     */
    public function actionMultipleAdd()
    {
        $productIds = $this->request->post('data');
        $customerId = Yii::$app->user->identity->id;
        try {
            $ids = Json::decode($productIds);
            $data = [];
            foreach ($ids as $product) {
                $data[] = new Favorite([
                    'product_id' => $product['product_id'],
                    'customer_id' => $customerId,
                    'created_at' => time(),
                ]);
            }
            Favorite::multipleAdd($data);
        } catch (Exception $exception) {
        }
        return Product::findWithCustomerFavorite($customerId);
    }
}