<?php

namespace backend\controllers;

use backend\models\Product;
use common\models\ActiveQuery;
use common\models\Discount;
use common\models\ProductDiscount;
use common\util\Status;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class DiscountController
 * @package backend\controllers
 */
class DiscountController extends Controller
{
    use ActionEditStatus;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [];
        return ArrayHelper::merge(parent::behaviors(), $behaviors);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Discount::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Discount();

        if ($model->load(Yii::$app->request->post()) && $model->saveAs()) {
            Yii::$app->session->setFlash('success', 'Create successful');
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->saveAs()) {
            Yii::$app->session->setFlash('success', 'Update successful');
            return $this->redirect(['update', 'id' => $model->id]);
        }


        $filter = $this->request->get();
        if (!empty($filter['id'])) unset($filter['id']);

        $productProvider = new ActiveDataProvider([
            'query' => $this->findProducts($filter, $id),
        ]);

        return $this->render('update', [
            'model' => $model,
            'productProvider' => $productProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * @param $id
     * @return Discount|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Discount::find()->notDeleted()->andWhere(['id' => $id])->one();
        if ($model !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'Trang không tồn tại.'));
    }

    /**
     * @param $discount
     * @return string
     * @throws Exception
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionProductIndex($discount)
    {
        if (!$this->request->isAjax) {
            $this->throwNotFound();
        }

        $filter = $this->request->get();
        $query = Product::findIndex($filter)
            ->joinWith(['productDiscounts productDiscount'])
            ->andWhere([
                'NOT IN',
                Product::$alias . '.id',
                ProductDiscount::find()->select(['product_id'])
                    ->andWhere(['AND',
                        ['discount_id' => $discount],
                        ['status' => Status::STATUS_ACTIVE],
                    ]),
            ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'class' => Pagination::class,
                'pageSize' => 30,
            ],
        ]);
        $sort = $dataProvider->sort;
        $sort->attributes['category_id'] = [
            'asc' => ['category.name' => SORT_ASC],
            'desc' => ['category.name' => SORT_DESC],
        ];
        $sort->defaultOrder = ['id' => SORT_DESC];
        $dataProvider->setSort($sort);
        $post = $this->request->post();
        if ($post && !empty($post['selection'])) {
            ProductDiscount::batchSave($post['selection'], $discount);
        }
        $allProductDiscount = ProductDiscount::find()
            ->select(['id', 'product_id', 'discount_id'])
            ->andWhere(['discount_id' => $discount])
            ->indexBy('product_id')
            ->asArray()
            ->all();
        return $this->renderAjax('_product_index', [
            'dataProvider' => $dataProvider,
            'allProductDiscount' => array_keys($allProductDiscount),
            'filter' => $filter,
        ]);
    }

    /**
     * @param null $discount
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionProductDiscount($discount)
    {
        if ($post = $this->request->post()) {
            $model = $this->findProductDiscountMapping($post['product_discount_id']);
            if (!empty($post['submit']) && $post['submit'] == 'DELETE') {
                $model->softDelete();
            } else {
                $model->load($post);
                $model->save();
            }
        }

        $filter = $this->request->get();
        if (!empty($filter['discount'])) unset($filter['discount']);

        $dataProvider = new ActiveDataProvider([
            'query' => $this->findProducts($filter, $discount),
            'pagination' => [
                'class' => Pagination::class,
                'pageSize' => 30,
            ],
        ]);
        $sort = $dataProvider->sort;
        $sort->attributes['category_id'] = [
            'asc' => ['category.name' => SORT_ASC],
            'desc' => ['category.name' => SORT_DESC],
        ];
        $sort->defaultOrder = ['id' => SORT_DESC];
        $dataProvider->setSort($sort);

        return $this->renderAjax('_product_list', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdateDiscountMapping($id)
    {
        $model = $this->findProductDiscountMapping($id);
        return $this->renderAjax('_form_discount_mapping', ['model' => $model]);
    }

    /**
     * @param $filter
     * @param $discount
     * @return ActiveQuery
     */
    protected function findProducts($filter, $discount)
    {
        return Product::findIndex($filter)
            ->joinWith(['productDiscounts productDiscount' => function ($q) use ($discount) {
                /** @var $q ActiveQuery */
                return $q->andOnCondition(['productDiscount.discount_id' => $discount]);
            }])->andWhere(['productDiscount.discount_id' => $discount])
            ->andWhere(['productDiscount.status' => Status::STATUS_ACTIVE]);
    }

    /**
     * @param $id
     * @return array|ProductDiscount|ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findProductDiscountMapping($id)
    {
        $model = ProductDiscount::find()->notDeleted()->andWhere(['id' => $id])->one();
        if ($model !== NULL) {
            return $model;
        }

        $this->throwNotFound();
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDeleteProductDiscount($id)
    {
        $model = $this->findProductDiscountMapping($id);
        return $this->renderAjax('_delete', [
            'discountId' => $model->discount->id,
            'id' => $id,
        ]);
    }
}
