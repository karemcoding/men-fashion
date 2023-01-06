<?php

namespace backend\controllers;

use common\models\coupons\BasicCoupon;
use common\models\coupons\CouponModel;
use common\models\coupons\FreeOneFeeCoupon;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class CouponController
 * @package backend\controllers
 */
class CouponController extends Controller
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
     * @param $id
     * @return array|CouponModel|ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = CouponModel::find()->andWhere(['id' => $id])->notDeleted()->one();
        if ($model !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }

    /**
     * Lists all CouponModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'types' => (new CouponModel())->findTemplates(),
        ]);
    }

    /**
     * @param $type
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionList($type)
    {
        switch ($type) {
            case FreeOneFeeCoupon::type():
                $all = FreeOneFeeCoupon::find()
                    ->joinWith(['couponProperties'])
                    ->andWhere(['type' => $type])
                    ->notDeleted()
                    ->all();
                foreach ($all as &$item) {
                    $item->convert();
                }

                $dataProvider = new ArrayDataProvider([
                    'allModels' => $all,
                    'modelClass' => FreeOneFeeCoupon::class,
                ]);
                return $this->render('free-one-fee/index', [
                    'dataProvider' => $dataProvider,
                ]);
            case BasicCoupon::type():
                $all = BasicCoupon::find()
                    ->joinWith(['couponProperties'])
                    ->andWhere(['type' => $type])
                    ->notDeleted()
                    ->all();
                foreach ($all as &$item) {
                    $item->convert();
                }

                $dataProvider = new ArrayDataProvider([
                    'allModels' => $all,
                    'modelClass' => BasicCoupon::class,
                ]);
                return $this->render('basic-coupon/index', [
                    'dataProvider' => $dataProvider,
                ]);
            default:
                throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
        }
    }

    /**
     * @param null $id
     * @return string|Response
     */
    public function actionFreeOneFee($id = NULL)
    {
        if ($id) {
            $model = FreeOneFeeCoupon::findOneWithProperty(['id' => $id]);
            $actionUpdate = TRUE;
        } else {
            $model = new FreeOneFeeCoupon([
                'type' => FreeOneFeeCoupon::type(),
                'model_class' => FreeOneFeeCoupon::class,
            ]);
            $actionUpdate = FALSE;
        }
        if ($model->load($this->request->post()) && $model->store()) {
            if ($actionUpdate) {
                Yii::$app->session->setFlash('success', 'Update successful');
            } else {
                Yii::$app->session->setFlash('success', 'Create successful');
            }
            return $this->redirect(['coupon/free-one-fee', 'id' => $model->id]);
        }
        return $this->render('free-one-fee/form', ['model' => $model]);
    }

    /**
     * @param null $id
     * @return string|Response
     */
    public function actionBasic($id = NULL)
    {
        if ($id) {
            $model = BasicCoupon::findOneWithProperty(['id' => $id]);
            $actionUpdate = TRUE;
        } else {
            $model = new BasicCoupon([
                'type' => BasicCoupon::type(),
                'model_class' => BasicCoupon::class,
            ]);
            $actionUpdate = FALSE;
        }
        if ($model->load($this->request->post()) && $model->store()) {
            if ($actionUpdate) {
                Yii::$app->session->setFlash('success', 'Update successful');
            } else {
                Yii::$app->session->setFlash('success', 'Create successful');
            }
            return $this->redirect(['coupon/basic', 'id' => $model->id]);
        }
        return $this->render('basic-coupon/form', ['model' => $model]);
    }
}
