<?php

namespace backend\controllers;

use backend\models\Product;
use common\models\InventoryHistory;
use common\models\InventoryReason;
use common\models\Order;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class ProductController
 * @package backend\controllers
 */
class ProductController extends Controller
{
    use ActionEditStatus;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), []);
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $filter = $this->request->get();
        $query  = Product::findIndex($filter);

        $dataProvider                    = new ActiveDataProvider([
                                                                      'query'      => $query,
                                                                      'pagination' => [
                                                                          'class'    => Pagination::class,
                                                                          'pageSize' => 30,
                                                                      ],
                                                                  ]);
        $sort                            = $dataProvider->sort;
        $sort->attributes['category_id'] = [
            'asc'  => ['category.name' => SORT_ASC],
            'desc' => ['category.name' => SORT_DESC],
        ];
        $sort->defaultOrder              = ['id' => SORT_DESC];
        $dataProvider->setSort($sort);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filter'       => $filter,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post())) {
            $model->image  = UploadedFile::getInstance($model, 'image');
            $model->images = UploadedFile::getInstances($model, 'images');
            if ($model->saveAsCreate()) {
                Yii::$app->session->setFlash('success', 'Create successful');

                return $this->redirect(['product/update', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->image  = UploadedFile::getInstance($model, 'image');
            $model->images = UploadedFile::getInstances($model, 'images');
            if ($model->saveAsUpdate()) {
                Yii::$app->session->setFlash('success', 'Update successful');

                return $this->redirect(['product/update', 'id' => $model->id]);
            }
        }

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $model->getInventoryHistory()
                                                       ->joinWith(['warehouse']),
                                                   'pagination' => [
                                                       'class'    => Pagination::class,
                                                       'pageSize' => 30,
                                                   ],
                                               ]);

        $sort                             = $dataProvider->sort;
        $sort->attributes['warehouse_id'] = [
            'asc'  => ['warehouse.name' => SORT_ASC],
            'desc' => ['warehouse.name' => SORT_DESC],
        ];
        $sort->defaultOrder               = ['id' => SORT_DESC];
        $dataProvider->setSort($sort);

        return $this->render('update', [
            'model'            => $model,
            'inventoryLogData' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->remove()) {
            Yii::$app->session->setFlash('success', 'Delete successful');
        } else {
            Yii::$app->session->setFlash('error', 'Can not delete');
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     *
     * @return Product|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }

    /**
     * @param $id
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionDeleteGallery($id)
    {
        $product                    = $this->findModel($id);
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'status' => $product->deleteGalleryOneImage($this->request->post('key') ?? ''),
        ];
    }

    /**
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionAddInventory($id)
    {
        $product = $this->findModel($id);
        $model   = new InventoryHistory([
                                            'product_id' => $product->id,
                                        ]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->add($product)) {
                Yii::$app->session->setFlash('success', 'Create successful');

                return $this->redirect(['product/update', 'id' => $product->id]);
            }
        }

        return $this->render('inventory_form', ['model' => $model, 'product' => $product]);
    }

    /**
     * @return string
     */
    public function actionRenderReasonField()
    {
        $reasons = InventoryReason::select2();
        $model   = new InventoryHistory();

        return $this->renderAjax('_reason_field', [
            'model'      => $model,
            'selections' => $reasons,
        ]);
    }

    /**
     * @return string
     */
    public function actionListOder()
    {
        $filter = $this->request->post();
        $query  = Order::find()
            ->alias('this')
            ->joinWith(['customer customer']);
        if (!empty($filter['number'])) {
            $query->andFilterWhere(['number' => $filter['number']]);
        }
        if (!empty($filter['customer'])) {
            $query->andFilterWhere(['LIKE', 'customer.name', $filter['customer']]);
        }
        $dataProvider                      = new ActiveDataProvider([
                                                                        'query'      => $query,
                                                                        'sort'       => [
                                                                            'defaultOrder' => ['id' => SORT_DESC],
                                                                        ],
                                                                        'pagination' => [
                                                                            'defaultPageSize' => 8,
                                                                        ],
                                                                    ]);
        $sort                              = $dataProvider->sort;
        $sort->attributes['customer_name'] = [
            'asc'  => ['customer.name' => SORT_DESC],
            'desc' => ['customer.name' => SORT_ASC],
        ];
        $dataProvider->setSort($sort);

        return $this->renderAjax('_list_order', [
            'dataProvider' => $dataProvider,
            'filter'       => $filter,
        ]);
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDuplicate($id)
    {
        $model    = $this->findModel($id);
        $newModel = new Product();
        $newModel->setAttributes($model->getAttributes());
        $newModel->sku = $model->sku . '-' . $model->id;
        $newModel->save();

        return $this->redirect(['product/update', 'id' => $newModel->id]);
    }
}
