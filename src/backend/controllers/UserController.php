<?php

namespace backend\controllers;

use backend\models\User;
use backend\util\Permissions;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class UserController
 *
 * @package backend\controllers
 */
class UserController extends Controller
{

    use ActionEditStatus;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => TRUE,
                        'permissions' => [Permissions::USER_INDEX],
                    ],
                    [
                        'actions' => ['create', 'update', 'validate', 'change-status'],
                        'allow' => TRUE,
                        'permissions' => [Permissions::USER_UPSERT],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => TRUE,
                        'permissions' => [Permissions::USER_DELETE],
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
     * @return string
     * SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
     * SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
     */
    public function actionIndex()
    {
        $query = User::find()
            ->alias('user')
            ->joinWith('role')
            ->notDeleted();
        $filter = Yii::$app->request->get();
        if (!empty($filter['username'])) {
            $query->andFilterWhere([
                'OR',
                ['LIKE', 'user.name', $filter['username']],
                ['LIKE', 'user.username', $filter['username']],
            ]);
        }
        if (!empty($filter['user_role'])) {
            $query->andFilterWhere(['user.role_id' => $filter['user_role']]);
        }
        if (!isset($filter['state'])) {
            $filter['state'] = '';
        }
        if ($filter['state'] != -1) {
            $query->andFilterWhere(['user.status' => $filter['state'] ?? NULL]);
        }
        $data_provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'class' => Sort::class,
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id',
                    'name',
                    'username',
                    'email',
                    'status',
                    'created_at',
                    'role_id' => [
                        'ASC' => ['role.name' => SORT_ASC],
                        'DESC' => ['role.name' => SORT_DESC]
                    ]
                ]
            ]
        ]);

        return $this->render('index', [
            'data_provider' => $data_provider,
            'filter' => $filter,

        ]);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new User(['scenario' => User::SCENARIO_CREATE]);
        $post = Yii::$app->request->post();
        if ($post) {
            if ($model->load($post) && $model->add()) {
                Yii::$app->session->setFlash('success', 'Create successful');
                return $this->redirect(['user/index']);
            }
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
        $post = Yii::$app->request->post();
        if ($post) {
            if ($model->load($post) && $model->edit()) {
                Yii::$app->session->setFlash('success', 'Update successful');
                return $this->redirect(['user/update', 'id' => $id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return User|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = User::find()->andWhere(['id' => $id])->notDeleted()->one();
        $model->scenario = User::SCENARIO_UPDATE;
        if ($model !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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
            $model = new User();
            $model->scenario = User::SCENARIO_CREATE;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        return [];
    }
}
