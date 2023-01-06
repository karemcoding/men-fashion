<?php

namespace backend\controllers;

use common\models\ProductBrand;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ProductBrandController
 * @package backend\controllers
 */
class ProductBrandController extends Controller
{
    use ActionEditStatus;

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = [];
        return ArrayHelper::merge(parent::behaviors(), $behaviors);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $query = ProductBrand::find()->notDeleted();
        $filter = $this->request->get();
        if (!empty($filter['name'])) {
            $query->andFilterWhere(['LIKE', ProductBrand::$alias . '.name', $filter['name']]);
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
        $model = new ProductBrand();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return ProductBrand
     * @throws NotFoundHttpException
     */
    protected function findModel($id): ProductBrand
    {
        $model = ProductBrand::find()->andWhere(['id' => $id])->notDeleted()->one();
        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }
}
