<?php

namespace backend\controllers;

use common\models\Message;
use common\models\SourceMessage;
use common\util\LanguageSelector;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MultiLanguageController extends Controller
{
    /**
     * @return array|array[]
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']['actions']['set'] = ['POST'];
        return $behaviors;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $query = Message::find()
            ->alias('this')
            ->joinWith(['source source']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionAddSource()
    {
        $model = new Message(['scenario' => Message::SCENARIO_ADD_SOURCE]);
        if ($model->load(Yii::$app->request->post()) && $model->add()) {
            Yii::$app->session->setFlash('success', 'Create successful');
            return $this->redirect(['index']);
        }
        return $this->renderAjax('_add_source', ['model' => $model,]);
    }

    /**
     * @param $source
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionEditSource($source)
    {
        if (!$this->request->isAjax) {
            $this->redirect(['multi-language/index']);
        }
        $sourceObj = $this->findSource($source);
        if ($sourceObj->load(Yii::$app->request->post()) && $sourceObj->save()) {
            return $this->redirect(['index']);
        } elseif ($errors = $sourceObj->errors) {
            foreach ($errors as $error) {
                Yii::$app->session->setFlash('error', $error[0]);
            }
        }
        return $this->renderAjax('_edit-source', ['model' => $sourceObj]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDeleteSource($id)
    {
        $model = $this->findSource($id);
        $postId = $this->request->post('id');
        if ($postId == $model->id) {
            if ($model->delete()) {
                return $this->redirect(['index']);
            }
        }

        return $this->renderAjax('_delete', ['id' => $model->id]);
    }

    /**
     * @param $source
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionAdd($source)
    {
        if (!$this->request->isAjax) {
            $this->redirect(['multi-language/index']);
        }
        $sourceObj = $this->findSource($source);
        $model = new Message(['id' => $sourceObj->id]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Create successful');
            return $this->redirect(['index']);
        } elseif ($errors = $model->errors) {
            foreach ($errors as $error) {
                Yii::$app->session->setFlash('error', $error[0]);
            }
        }
        return $this->renderAjax('_add', ['model' => $model]);
    }

    /**
     * @param $id
     * @param $language
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id, $language)
    {
        if (!$this->request->isAjax) {
            $this->redirect(['multi-language/index']);
        }
        $model = $this->findModel($id, $language);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } elseif ($errors = $model->errors) {
            foreach ($errors as $error) {
                Yii::$app->session->setFlash('error', $error[0]);
            }
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @param $language
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDelete($id, $language)
    {
        $model = $this->findModel($id, $language);
        $postId = $this->request->post('id');
        if ($postId == $model->id) {
            $model->delete();
            if (count($model->source->messages) == 0) {
                $model->source->delete();
            }
            return $this->redirect(['index']);
        }

        return $this->renderAjax('_delete', ['id' => $model->id]);
    }

    /**
     * @param $code
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionSet($code)
    {
        if ($code) {
            /** @var LanguageSelector $languageSelector */
            $languageSelector = Yii::$app->get('languageSelector');
            Yii::$app->session->set($languageSelector->sessionName, $code);
        }
        return $this->redirect($this->request->referrer);
    }

    /**
     * @param $id
     * @param $language
     * @return Message|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $language)
    {
        if (($model = Message::findOne(['id' => $id, 'language' => $language])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }

    /**
     * @param $id
     * @return SourceMessage|null
     * @throws NotFoundHttpException
     */
    protected function findSource($id)
    {
        if (($model = SourceMessage::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
    }
}
