<?php

namespace backend\controllers;

use common\models\CustomerGroup;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class CustomerGroupController
 * @package backend\controllers
 */
class CustomerGroupController extends Controller
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
        $query = CustomerGroup::find()->notDeleted();
        $filter = Yii::$app->request->get();
        if (!empty($filter['name'])) {
            $query->andFilterWhere(['LIKE', CustomerGroup::$alias . '.name', $filter['name']]);
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
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        $model = new CustomerGroup();
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Create successful');
            } elseif ($errors = $model->firstErrors) {
                foreach ($errors as $error) {
                    Yii::$app->session->addFlash('error', $error);
                }
            }
            return $this->redirect($this->request->referrer);
        }

        if (!$this->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
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

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Update successful');
            } elseif ($errors = $model->firstErrors) {
                foreach ($errors as $error) {
                    Yii::$app->session->addFlash('error', $error);
                }
            }
            return $this->redirect($this->request->referrer);
        }

        if (!$this->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return CustomerGroup|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = CustomerGroup::find()->notDeleted()->andWhere(['id' => $id])->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'Trang không tồn tại.'));
    }
}
