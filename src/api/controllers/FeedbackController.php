<?php

namespace api\controllers;

use common\models\Feedback;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class FeedbackController
 * @package api\controllers
 */
class FeedbackController extends ActiveController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbFilter']['actions'] = [
            'add' => ['POST'],
        ];
        $behaviors['authenticator'] = ['class' => HttpBearerAuth::class];
        return $behaviors;
    }

    /**
     * @return array|Feedback[]|ActiveRecord[]
     */
    public function actionAdd()
    {
        $product_id = $this->request->post('product_id');
        $score = $this->request->post('score');
        $feedback = $this->request->post('feedback');
        if ($product_id && $feedback) {
            $model = new Feedback([
                'customer_id' => Yii::$app->user->identity->id,
                'product_id' => $product_id,
                'score' => $score ?? 5,
                'feedback' => $feedback,
            ]);
            if ($model->save()) {
                return Feedback::find()->andWhere(['id' => $model->id])->asArray()->one();
            } else {
                return $model->firstErrors;
            }
        }
        return NULL;
    }

    public function actionEdit()
    {
        $id = $this->request->post('id');
        $score = $this->request->post('score');
        $detail = $this->request->post('feedback');
        $feedback = Feedback::findOne(['id' => $id,
            'customer_id' => Yii::$app->user->identity->id]);
        if ($feedback) {
            $feedback->score = (int)$score;
            $feedback->feedback = $detail;
            if ($feedback->dirtyAttributes) {
                if ($feedback->save()) {
                    return $feedback;
                } else {
                    return $feedback->firstErrors;
                }
            }
        }
        return NULL;
    }

    /**
     * @return bool
     * @throws Throwable
     */
    public function actionRemove()
    {
        $id = $this->request->post('id');
        $feedback = Feedback::findOne(['id' => $id,
            'customer_id' => Yii::$app->user->identity->id]);
        if ($feedback) {
            try {
                $feedback->delete();
            } catch (Exception $exception) {
                return FALSE;
            }
        }
        return TRUE;
    }
}