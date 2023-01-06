<?php

namespace backend\controllers;

use common\models\ProductSupplier;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ProductSupplierController
 * @package backend\controllers
 */
class ProductSupplierController extends Controller
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
        $query = ProductSupplier::find()->notDeleted();
        $filter = $this->request->get();
        if (!empty($filter['name'])) {
            $query->andFilterWhere(['LIKE', ProductSupplier::$alias . '.name', $filter['name']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 30,
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
        $model = new ProductSupplier();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Update successful');
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return ProductSupplier|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = ProductSupplier::find()->notDeleted()->andWhere(['id' => $id])->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }
}
