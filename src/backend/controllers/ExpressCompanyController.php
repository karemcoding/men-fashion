<?php

namespace backend\controllers;

use common\models\ExpressCompany;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ExpressCompanyController
 * @package backend\controllers
 */
class ExpressCompanyController extends Controller
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
     * Lists all ExpressCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = ExpressCompany::find()->notDeleted();
        $filter = $this->request->get();
        if (!empty($filter['name'])) {
            $query->andFilterWhere(['LIKE', ExpressCompany::$alias . '.name', $filter['name']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new ExpressCompany();

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
     * @return ExpressCompany|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = ExpressCompany::find()->notDeleted()->andWhere(['id' => $id])->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }
}
