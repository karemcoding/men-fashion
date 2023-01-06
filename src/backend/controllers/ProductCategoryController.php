<?php

namespace backend\controllers;

use common\models\ProductCategory;
use common\util\Status;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class ProductCategoryController
 * @package backend\controllers
 */
class ProductCategoryController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => TRUE,
                    ],
                ],
            ]
        ];

        return ArrayHelper::merge(parent::behaviors(), $behaviors);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $query = ProductCategory::find()
            ->notDeleted()
            ->disableRoot()
            ->joinWith(['products']);
        $filter = $this->request->get();
        if (!empty($filter['name'])) {
            $query->andFilterWhere(['LIKE', ProductCategory::$alias . '.name', $filter['name']]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'class' => Pagination::class,
                'pageSize' => 30,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new ProductCategory();

        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->saveAsCreate()) {
                Yii::$app->session->setFlash('success', 'Create successful');
                return $this->redirect(['product-category/update', 'id' => $model->id]);
            }
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
        $model->parent = $model->parents(1)->one()->id ?? '';

        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->saveAsUpdate()) {
                Yii::$app->session->setFlash('success', 'Update successful');
                return $this->redirect(['product-category/update', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return array|ProductCategory|ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = ProductCategory::find()->andWhere(['id' => $id])
            ->notDeleted()
            ->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
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
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionChangeStatus()
    {
        if (!$this->request->isAjax) {
            throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
        }
        $get = $this->request->post('request');
        $model = $this->findModel($get);
        if ($model->status == Status::STATUS_ACTIVE) {
            $model->status = Status::STATUS_INACTIVE;
        } else {
            $model->status = Status::STATUS_ACTIVE;
        }
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Update successful');
        } else {
            Yii::$app->session->setFlash('error', 'Can not delete');
        }
        return $this->redirect($this->request->referrer);
    }
}
