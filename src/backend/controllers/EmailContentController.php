<?php

namespace backend\controllers;

use common\models\mailer\EmailContent;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class EmailContentController
 * @package backend\controllers
 */
class EmailContentController extends Controller
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
        $dataProvider = new ArrayDataProvider([
            'allModels' => (new EmailContent())->findTemplates(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $key
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($key)
    {
        $model = $this->findModel($key);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Update Successful');
            return $this->refresh();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $key
     * @return EmailContent|null
     * @throws NotFoundHttpException
     */
    protected function findModel($key)
    {
        if (($model = EmailContent::findOne(['template' => $key])) !== NULL) {
            return $model;
        }
        $model = new EmailContent(['template' => $key]);
        if ($model->templateObj == NULL) {
            throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
        }
        return $model;
    }
}
