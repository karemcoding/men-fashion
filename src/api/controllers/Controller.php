<?php

namespace api\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class Controller
 * @package api\controllers
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
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => ['class' => VerbFilter::class],
        ];
    }
}
