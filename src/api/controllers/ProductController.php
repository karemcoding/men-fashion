<?php
/**
 * @author      ANDY <ltanh1194@gmail.com>
 * @date        11:29 AM 5/3/2021
 * @projectName baseProject by ANDY
 */

namespace api\controllers;

use api\models\Product;
use Exception;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class ProductController
 * @package api\controllers
 */
class ProductController extends ActiveController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors                          = parent::behaviors();
        $behaviors['verbFilter']['actions'] = [
            '*'             => ['GET'],
            'find-multiple' => ['POST'],
        ];

        return $behaviors;
    }

    /**
     * @param int  $limit
     * @param int  $offset
     * @param null $category
     * @param null $ignore
     *
     * @return Product[]|array|ActiveRecord[]
     */
    public function actionIndex($limit = 10, $offset = 0, $category = 1, $ignore = null, $sort = 1, $filter = null,$search = null)
    {
        return Product::getList($limit, $offset, $category, $ignore, $sort,$filter,$search);
    }

    /**
     * @return Product[]|array|ActiveRecord[]
     */
    public function actionMaybeLike()
    {
        return Product::findMaybeLike();
    }

    /**
     * @param $id
     *
     * @return array|Product|ActiveRecord|null
     */
    public function actionOne($id)
    {
        if (Yii::$app->user->isGuest) {
            $query = Product::find()
                ->selectDefault()
                ->addSelect(['this.gallery', 'this.description', 'this.parent_id', 'this.size'])
                ->withCategory()
                ->joinWith([
                               'feedbacks feedbacks' => function (ActiveQuery $q) {
                                   $q->joinWith(['customer customer'], false)
                                       ->select(['feedbacks.*'])
                                       ->addSelect(['user' => 'customer.name'])
                                       ->orderBy(['feedbacks.id' => SORT_DESC]);
                               },
                           ])
                ->andWhere(['this.id' => $id])
                ->asArray()
                ->one();
        } else {
            $query = Product::find()
                ->selectDefault()
                ->addSelect(['this.gallery', 'this.description', 'this.parent_id'])
                ->withCategory()
                ->joinWith([
                               'carts carts' => function (ActiveQuery $q) {
                                   $q->andOnCondition(['carts.customer_id' => Yii::$app->user->id]);
                               },
                           ])
                ->joinWith([
                               'feedbacks feedbacks' => function (ActiveQuery $q) {
                                   $q->joinWith(['customer customer'], false)
                                       ->select(['feedbacks.*'])
                                       ->addSelect(['user' => 'customer.name'])
                                       ->orderBy(['feedbacks.id' => SORT_DESC]);
                               },
                           ])
                ->andWhere(['this.id' => $id])
                ->asArray()
                ->one();
        }
        if ($query == null) {
            return null;
        }
        $cart      = $query['carts'][0] ?? null;
        $feedbacks = $query['feedbacks'];
        unset($query['carts']);
        unset($query['feedbacks']);
        $product = $query;

        if ($product['parent_id'] == null) {
            $size         = Product::find()
                ->andWhere(['product.parent_id' => $id])
                ->indexBy('product.id')
                ->asArray()
                ->all();
            $size         = ArrayHelper::map($size, 'id', 'size');
            $p            = Product::findOne(['product.id' => $id]);
            $size[$p->id] = $p->size;
        } else {
            $size = Product::find()
                ->andWhere(['product.parent_id' => $product['parent_id']])
                ->indexBy('product.id')
                ->asArray()
                ->all();
            $size         = ArrayHelper::map($size, 'id', 'size');
            $p            = Product::findOne(['product.id' => $product['parent_id']]);
            $size[$p->id] = $p->size;
        }

        return [
            'product'   => $product,
            'cart'      => $cart,
            'feedbacks' => $feedbacks,
            'related'   => $size,
        ];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function actionListHot($limit=null)
    {
        return Product::findHot($limit);
    }

    /**
     * @param $keyword
     *
     * @return Product[]|array|ActiveRecord[]
     */
    public function actionSearch($keyword)
    {
        return Product::find()
            ->selectDefault()
            ->andFilterWhere(['LIKE', 'this.name', $keyword])
            ->withCategory()
            ->asArray()
            ->all();
    }

    /**
     * @return Product[]|array|ActiveRecord[]
     */
    public function actionFindMultiple()
    {
        return Product::findMultiple(Json::decode($this->request->post('ids')));
    }
}