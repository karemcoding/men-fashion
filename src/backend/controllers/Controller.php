<?php

namespace backend\controllers;

use backend\models\DynamicOrder;
use Yii;
use yii\base\Action;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class Controller
 *
 * @package backend\base
 */
class Controller extends \yii\web\Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'class' => 'yii\filters\AccessRule',
                        'allow' => TRUE,
                        'permissions' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'logout' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param Action $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->controller->id != 'order') {
            DynamicOrder::removeSession();
        } else {
            if ($action->id == 'create') {
                if (($this->request->referrer && !$this->request->isPost)
                    || empty($this->request->referrer)) {
                    DynamicOrder::removeSession();
                }
            } elseif (in_array($action->id, ['index', 'update', 'view'])) {
                DynamicOrder::removeSession();
            }
        }
        return parent::beforeAction($action);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function throwNotFound()
    {
        throw new NotFoundHttpException(Yii::t('common', 'Trang không tồn tại'));
    }
}
