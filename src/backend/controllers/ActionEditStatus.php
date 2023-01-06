<?php

namespace backend\controllers;

use common\util\Status;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Trait ActionChangeStatus
 * @package backend\controllers
 */
trait ActionEditStatus
{
    /**
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionChangeStatus(): Response
    {
        if (!$this->request->isAjax) {
            throw new NotFoundHttpException(Yii::t('common', 'Yêu cầu không tồn tại.'));
        }
        $get = $this->request->post('request');
        $model = $this->findModel($get);
        if ($model->status == Status::STATUS_ACTIVE) {
            $model->status = Status::STATUS_INACTIVE;
        } else {
            $model->status = Status::STATUS_ACTIVE;
        }
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Cập nhật trạng thái thành công!');
        } else {
            Yii::$app->session->setFlash('error', 'Cập nhật trạng thái không thành công!');
        }
        return $this->redirect($this->request->referrer);
    }

    /**
     * @param $id
     * @return Response
     */
    public function actionDelete($id): Response
    {
        if ($this->findModel($id)->softDelete()) {
            Yii::$app->session->setFlash('success', 'Đã xóa');
        } else {
            Yii::$app->session->setFlash('error', 'Không xóa được');
        }

        return $this->redirect($this->request->referrer);
    }
}