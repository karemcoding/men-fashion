<?php

namespace backend\controllers;

use common\models\OrderReturns;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class OrderReturnsController
 * @package backend\controllers
 */
class OrderReturnsController extends Controller
{
    /**
     * @return array|array[]
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
            'query' => OrderReturns::find()->joinWith(['order']),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $order_id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate($order_id)
    {
        $model = new OrderReturns([
            'order_id' => $order_id,
            'status' => OrderReturns::STATUS_APPLIED,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        if (!$this->request->isAjax) {
            $this->throwNotFound();
        }
        return $this->renderAjax('_form', [
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
        $model->scenario = OrderReturns::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post()) && $model->saveAsStatus()) {
            return $this->redirect(['index']);
        }

        if (!$this->request->isAjax) {
            $this->throwNotFound();
        }
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->renderAjax('_view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return OrderReturns|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = OrderReturns::find()->andWhere(['id' => $id])->notDeleted()->one();
        if ($model !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }
}
