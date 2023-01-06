<?php

namespace backend\controllers;

use backend\models\Customer;
use backend\models\DynamicOrder;
use backend\models\DynamicOrderAuditPaymentStatus;
use backend\models\DynamicOrderAuditStatus;
use backend\models\Product;
use common\models\coupons\CouponModel;
use common\models\Fee;
use common\models\Order;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\data\Sort;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class OrderController
 * @package backend\controllers
 */
class OrderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [];
        return ArrayHelper::merge(parent::behaviors(), $behaviors);
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Order::find()
            ->alias('this')
            ->joinWith(['customer customer']);
        $filter = $this->request->get();
        if (!empty($filter['number'])) {
            $query->andFilterWhere(['number' => $filter['number']]);
        }
        if (!empty($filter['customer'])) {
            $query->andFilterWhere(['LIKE', 'customer.name', $filter['customer']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
        $sort = $dataProvider->sort;
        $sort->attributes['customer_name'] = [
            'asc' => ['customer.name' => SORT_DESC],
            'desc' => ['customer.name' => SORT_ASC],
        ];
        $dataProvider->setSort($sort);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    public function actionProductIndex()
    {
        $filter = $this->request->get();
        $query = Product::findIndex($filter);

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

        return $this->renderAjax('_product_index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * @param $ids
     * @return array|Product[]|ActiveRecord[]
     */
    protected function findProduct($ids)
    {
        return Product::find()
            ->andWhere([Product::$alias . '.id' => $ids])
            ->indexBy('id')
            ->all();
    }

    /**
     * @param $ids
     * @return array|Fee[]|ActiveRecord[]
     */
    protected function findFee($ids)
    {
        return Fee::find()
            ->andWhere([Fee::$alias . '.id' => $ids])
            ->indexBy('id')
            ->all();
    }

    /**
     * @param $ids
     * @return array|CouponModel[]|ActiveRecord[]
     */
    protected function findCoupons($ids)
    {
        return CouponModel::find()
            ->andWhere([CouponModel::$alias . '.id' => $ids])
            ->indexBy('id')
            ->all();
    }

    /**
     * @param $id
     * @param $status
     * @return string|Response
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $status)
    {
        $model = $this->findModel($id);
        $model->status = $status;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->saveAsStatus()) {
                Yii::$app->session->setFlash('success', 'Update successful');
            } elseif ($errors = $model->errors) {
                foreach ($errors as $error) {
                    Yii::$app->session->addFlash('error', $error[0]);
                }
            }
            return $this->redirect($this->request->referrer);
        }
        if (!$this->request->isAjax) {
            throw new BadRequestHttpException();
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdatePaymentStatus($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Update successful');
                return $this->redirect($this->request->referrer);
            } elseif ($errors = $model->errors) {
                foreach ($errors as $error) {
                    Yii::$app->session->addFlash('error', $error[0]);
                }
            }
        }
        if (!$this->request->isAjax) {
            throw new BadRequestHttpException();
        }

        return $this->renderAjax('_form_payment_status', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $query = $model->getAudit()
            ->orderBy(['id' => SORT_ASC])
            ->all();
        $tracking = new ArrayDataProvider([
            'allModels' => DynamicOrderAuditStatus::generate($query),
            'sort' => [
                'class' => Sort::class,
                'defaultOrder' => [
                    'time' => SORT_DESC,
                ],
                'attributes' => [
                    'time', 'status', 'staff', 'remark',
                ],
            ],
        ]);
        $trackingPaymentStatus = new ArrayDataProvider([
            'allModels' => DynamicOrderAuditPaymentStatus::generate($query),
            'sort' => [
                'class' => Sort::class,
                'defaultOrder' => [
                    'time' => SORT_DESC,
                ],
                'attributes' => [
                    'time', 'payment_status', 'payment_method', 'staff', 'remark',
                ],
            ],
        ]);
        return $this->render('view', [
            'model' => $model,
            'tracking' => $tracking,
            'trackingPaymentStatus' => $trackingPaymentStatus,
        ]);
    }

    /**
     * @param $id
     * @return Order|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Order::find()
            ->alias('this')
            ->with([
                'orderFees' => function ($q) {
                    $q->with(['fee']);
                },
                'details' => function ($q) {
                    $q->with(['product']);
                },
            ])
            ->andWhere(['this.id' => $id])->one();
        if ($model !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }

    /**
     * @return string
     */
    public function actionCreate()
    {
        $session = Yii::$app->session;
        if ($ssModel = $session->get(DynamicOrder::OBJECT)) {
            $model = $ssModel;
        } else {
            $model = new DynamicOrder();
        }

        /** Kiểm tra nếu có sản phẩm trong session */
        if ($ids = $session->get(DynamicOrder::ORDER_PRODUCT_LIST)) {
            $model->products = $this->findProduct($ids);
            $model->productIds = $ids;
        }
        /** Kiểm tra nếu có phí trong session */
        if ($feeIds = $session->get(DynamicOrder::FEE_LIST)) {
            $model->fees = $this->findFee($feeIds);
            $model->feeIds = $feeIds;
        }
        /** Kiểm tra nếu có coupon trong session */
        if ($couponIds = $session->get(DynamicOrder::COUPON_LIST)) {
            $model->coupons = $this->findCoupons($couponIds);
            $model->couponIds = $couponIds;
        }

        $post = $this->request->post();
        if ($post && !empty($post['submitBtn'])) {
            /** submit để add product */
            if ($post['submitBtn'] == 'productList') {
                $postData = $post['selection'] ?? [];
                $session->set(DynamicOrder::ORDER_PRODUCT_LIST, $postData);
                $model->products = $this->findProduct($postData);
                $model->productIds = $postData;
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            /** Submit để add các loại phí */
            if ($post['submitBtn'] == 'feeList') {
                $postData = $post['selectionFee'] ?? [];
                $session->set(DynamicOrder::FEE_LIST, $postData);
                $model->fees = $this->findFee($postData);
                $model->feeIds = $postData;
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            /** Submit để add các loại coupon */
            if ($post['submitBtn'] == 'couponList') {
                $postData = $post['selectionCoupon'] ?? [];
                $session->set(DynamicOrder::COUPON_LIST, $postData);
                $model->coupons = $this->findCoupons($postData);
                $model->couponIds = $postData;
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            /** Submit để tạo order */
            if ($post['submitBtn'] == 'orderCreate') {
                $model->productQuantity = $post['productQuantity'] ?? NULL;
                $session->set(DynamicOrder::PRODUCT_QUANTITY, $model->productQuantity);
                if ($model->load($post)
                    && $model->validateQuantity()
                    && $model->validateCoupon()
                    && $model->saveSession()) {
                    $session->set(DynamicOrder::OBJECT, $model);
                    return $this->redirect(['order/preview']);
                } elseif ($es = $model->firstErrors) {
                    foreach ($es as $item) {
                        Yii::$app->session->addFlash('error', $item);
                    }
                }
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionPreview()
    {
        /** @var DynamicOrder $model */
        $model = Yii::$app->session->get(DynamicOrder::OBJECT);
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
        }
        if ($this->request->post() && $model->save()) {
            DynamicOrder::removeSession();
            Yii::$app->session->setFlash('success', 'Create successful');
            return $this->redirect(['order/index']);
        }
        return $this->render('view_temp', ['model' => $model]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionSetQuantity()
    {
        if (!$this->request->isAjax) {
            throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
        }
        $session = Yii::$app->session;
        if ($post = $this->request->post()) {
            $quantity = $session->get(DynamicOrder::PRODUCT_QUANTITY);
            $quantity[$post['product']] = $post['quantity'];
            $session->set(DynamicOrder::PRODUCT_QUANTITY, $quantity);
        }
        return 200;
    }

    /**
     * @return string
     */
    public function actionCustomerList()
    {
        $filter = Yii::$app->request->get();
        $query = Customer::find()->alias('customer')->notDeleted();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        return $this->renderAjax('_customer_index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * @return string
     */
    public function actionFeeList()
    {
        $filter = Yii::$app->request->get();
        $query = Fee::find()->notDeleted();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        return $this->renderAjax('_fee_index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * @return string
     */
    public function actionCouponList()
    {
        $filter = Yii::$app->request->get();
        $query = CouponModel::find()->notDeleted();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        return $this->renderAjax('_coupon_index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }
}
