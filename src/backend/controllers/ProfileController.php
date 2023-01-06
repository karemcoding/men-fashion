<?php

namespace backend\controllers;

use backend\models\Profile;
use backend\models\User;
use Yii;
use yii\base\Exception;
use yii\web\Response;
use yii\web\UploadedFile;

class ProfileController extends Controller
{
    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionIndex()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $userAttribute = $user->attributes;
        $model = new Profile([
            'id' => $user->id,
            'username' => $user->username,
            'role' => $user->role->name,
        ]);
        $model->setAttributes($userAttribute);
        if ($model->load(Yii::$app->request->post())) {
            $model->fileAvatar = UploadedFile::getInstance($model, 'fileAvatar');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Update successful');
                return $this->refresh();
            }
        }
        return $this->render('index', ['model' => $model]);
    }
}