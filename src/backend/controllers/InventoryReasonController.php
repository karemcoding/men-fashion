<?php

namespace backend\controllers;

use common\models\InventoryReason;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class InventoryReasonController
 * @package backend\controllers
 */
class InventoryReasonController extends Controller
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
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => InventoryReason::find()->notDeleted(),
        ]);
        return $this->renderAjax('index', [
            'dataProvider' => $dataProvider,
            'model' => new InventoryReason()
        ]);
    }

    /**
     * @return string
     * Tại vì biến dataProvider đc cập nhật lại
     */
    public function actionCreate()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => InventoryReason::find()->notDeleted(),
        ]);

        $model = new InventoryReason();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model = new InventoryReason();
        }

        return $this->renderAjax('index', [
            'dataProvider' => $dataProvider,
            'model' => $model
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
            $dataProvider = new ActiveDataProvider([
                'query' => InventoryReason::find()->notDeleted(),
            ]);
            $model = new InventoryReason();
            return $this->renderAjax('index', [
                'dataProvider' => $dataProvider,
                'model' => $model
            ]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionRemove($id)
    {
        $this->findModel($id)->softDelete();
        $dataProvider = new ActiveDataProvider([
            'query' => InventoryReason::find()->notDeleted(),
        ]);

        return $this->renderAjax('index', [
            'dataProvider' => $dataProvider,
            'model' => new InventoryReason()
        ]);
    }

    /**
     * @param $id
     * @return InventoryReason|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = InventoryReason::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }
}
