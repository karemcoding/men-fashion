<?php

namespace backend\controllers;

use backend\models\Customer;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Customer::find()->alias('customer')->notDeleted()->joinWith('group customerGroup');
        $filter = Yii::$app->request->get();
        if (!empty($filter['keyword'])) {
            $query->andFilterWhere([
                'OR',
                ['LIKE', 'customer.name', $filter['keyword']],
                ['LIKE', 'customer.email', $filter['keyword']],
            ]);
        }
        if (!empty($filter['group'])) {
            $query->andFilterWhere(['customer.group_id' => $filter['group']]);
        }
        if (!isset($filter['state'])) {
            $filter['state'] = '';
        }
        if ($filter['state'] != -1) {
            $query->andFilterWhere(['customer.status' => $filter['state'] ?? NULL]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $sort = $dataProvider->sort;
        $sort->attributes['group'] = [
            'asc' => ['customerGroup.name' => SORT_ASC],
            'desc' => ['customerGroup.name' => SORT_DESC],
        ];
        $sort->defaultOrder = ['id' => SORT_DESC];
        $dataProvider->setSort($sort);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new Customer(['scenario' => Customer::SCENARIO_CREATE]);

        if ($model->load(Yii::$app->request->post()) && $model->add()) {
            Yii::$app->session->setFlash('success', 'Create successful');
            return $this->redirect(['customer/update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Customer::SCENARIO_UPDATE;
        if ($model->load(Yii::$app->request->post()) && $model->edit()) {
            Yii::$app->session->setFlash('success', 'Update successful');
            return $this->redirect(['customer/update', 'id' => $model->id]);
        }
        $data = new ActiveDataProvider([
            'query' => $model->getOrders(),
        ]);

        return $this->render('update', [
            'model' => $model,
            'data' => $data,
        ]);
    }

    /**
     * @param $id
     * @return Customer|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Customer::find()->andWhere(['id' => $id])->notDeleted()->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'Trang không tồn tại.'));
    }

    /**
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionValidate($id = 0)
    {
        if (!empty($id)) {
            $model = $this->findModel($id);
        } else {
            $model = new Customer();
            $model->scenario = Customer::SCENARIO_CREATE;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        return [];
    }
}
