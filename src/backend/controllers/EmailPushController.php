<?php

namespace backend\controllers;

use backend\models\Customer;
use common\models\CustomerGroup;
use common\models\mailer\DynamicPush;
use common\models\mailer\EmailHistory;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class EmailPushController
 * @package backend\controllers
 */
class EmailPushController extends Controller
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
     * @return string|Response
     * @throws InvalidConfigException
     */
    public function actionIndex()
    {
        $model = new DynamicPush();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->handle()) {
                Yii::$app->session->setFlash('success', 'Push Successful');
                return $this->refresh();
            } elseif ($es = $model->firstErrors) {
                foreach ($es as $item) {
                    Yii::$app->session->addFlash('error', $item);
                }
            }

        }
        return $this->render('push', ['model' => $model,]);
    }

    /**
     * @return string
     */
    public function actionCustomerList()
    {
        $filter = Yii::$app->request->get();
        $query = Customer::find()->alias('customer')->notDeleted();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        return $this->renderAjax('_customer_index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * @return string
     */
    public function actionCustomerGroupList()
    {
        $filter = Yii::$app->request->get();
        $query = CustomerGroup::find()->active();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        return $this->renderAjax('_customer_group_index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter,
        ]);
    }

    /**
     * @param $type
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionLoadIpt($type)
    {
        $model = new DynamicPush();
        if ($type == DynamicPush::SINGLE_RECEIVER) {
            return $this->render('_input_member',
                ['model' => $model]);
        }
        if ($type == DynamicPush::GROUP_RECEIVER) {
            return $this->render('_input_group',
                ['model' => $model]);
        }
        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }

    public function actionHistory()
    {
        $query = EmailHistory::find();
        $data = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('history', ['dataProvider' => $data]);
    }
}
