<?php

namespace backend\controllers;

use backend\util\Permissions;
use common\models\Permission;
use common\models\Role;
use common\models\RolePermission;
use common\util\Status;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class RoleController
 *
 * @package backend\controllers
 */
class RoleController extends Controller
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
                        'permissions' => [Permissions::ROLE_INDEX],
                    ],
                    [
                        'actions' => ['create', 'update', 'change-status'],
                        'allow' => TRUE,
                        'permissions' => [Permissions::ROLE_UPSERT],
                    ],
                    [
                        'actions' => ['permission'],
                        'allow' => TRUE,
                        'permissions' => [Permissions::ROLE_ACCESS],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => TRUE,
                        'permissions' => [Permissions::ROLE_DELETE],
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
     * Lists all Role models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Role::find()
            ->joinWith(['author author'])
            ->notDeleted();
        $filter = Yii::$app->request->get();
        if (!empty($filter['name'])) {
            $query->andFilterWhere(['LIKE', Role::$alias . '.name', $filter['name']]);
        }
        if (!isset($filter['state'])) {
            $filter['state'] = '';
        }
        if ($filter['state'] != -1) {
            $query->andFilterWhere([Role::$alias . '.status' => $filter['state'] ?? NULL]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filter' => $filter
        ]);
    }

    /**
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        $model = new Role();
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Create successful');
            } elseif ($errors = $model->firstErrors) {
                foreach ($errors as $error) {
                    Yii::$app->session->addFlash('error', $error);
                }
            }
            return $this->redirect($this->request->referrer);
        }

        if (!$this->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->isAdmin()) {
                $model->status = Status::STATUS_ACTIVE;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Update successful');
            } elseif ($errors = $model->firstErrors) {
                foreach ($errors as $error) {
                    Yii::$app->session->addFlash('error', $error);
                }
            }
            return $this->redirect($this->request->referrer);
        }

        if (!$this->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionPermission()
    {
        $roles = Role::find()->active()->all();
        $permissions = Permission::find()
            ->joinWith(['children child'])
            ->where([Permission::$alias . '.parent_id' => NULL])->all();

        if ($post = Yii::$app->request->post('access')) {
            $permission_data = [];

            foreach ($post as $role => $item) {
                $permission_deleted = RolePermission::deleteAll(['role_id' => $role]);

                foreach ($item as $permission_id => $value) {
                    if ($value == 1) {
                        $permission_data[] = new RolePermission([
                            'role_id' => $role,
                            'permission_id' => $permission_id
                        ]);
                    }
                }
            }

            if (!empty($permission_data)) {
                $upsert = RolePermission::updatePermission($permission_data);
            }

            if (!empty($upsert) || !empty($permission_deleted)) {
                Yii::$app->session->setFlash('success', 'Update successful');
            }

            return $this->refresh();
        }


        return $this->render('permission', ['roles' => $roles, 'permissions' => $permissions]);
    }

    /**
     * @param $id
     *
     * @return Role|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Role::find()->notDeleted()->andWhere(['id' => $id])->one();
        if ($model !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
