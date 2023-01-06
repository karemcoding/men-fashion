<?php

namespace backend\controllers;

use common\models\Fee;
use common\util\Status;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class FeeController
 * @package backend\controllers
 */
class FeeController extends Controller
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
     * Lists all Fee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Fee::find()->notDeleted(),
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
        $model = new Fee();

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
     * @return Fee|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Fee::find()->notDeleted()->andWhere(['id' => $id])->one();
        if ($model !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }

    /**
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionChangeFee()
    {
        if (!$this->request->isAjax) {
            throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
        }
        $get = $this->request->post('request');
        $model = $this->findModel($get);
        Fee::updateAll(['shipping_fee' => Status::STATUS_DELETED], ['shipping_fee' => Status::STATUS_ACTIVE]);
        if ($model->shipping_fee == Status::STATUS_ACTIVE) {
            $model->shipping_fee = Status::STATUS_DELETED;
        } else {
            $model->shipping_fee = Status::STATUS_ACTIVE;
        }
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Update successful');
        } else {
            Yii::$app->session->setFlash('error', 'Update fail');
        }
        return $this->redirect($this->request->referrer);
    }
}
