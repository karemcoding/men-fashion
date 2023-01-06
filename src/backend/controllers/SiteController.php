<?php

namespace backend\controllers;

use backend\models\Customer;
use backend\models\Product;
use backend\models\User;
use common\models\LoginForm;
use common\models\Order;
use common\models\ProductCategory;
use common\widgets\openstreetmap\ReverseAction;
use common\widgets\openstreetmap\SearchAddressAction;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Class SiteController
 *
 * @package backend\controllers
 */
class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'class'       => 'yii\filters\AccessRule',
                        'allow'       => true,
                        'actions'     => ['login', 'error'],
                        'permissions' => ['?'],
                    ],
                ],
            ],
        ];
        $behaviors = ArrayHelper::merge(parent::behaviors(), $behaviors);

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error'            => [
                'class'  => 'yii\web\ErrorAction',
                'layout' => 'login',
            ],
            'search-address'   => [
                'class' => SearchAddressAction::class,
            ],
            'reverse-latitude' => [
                'class' => ReverseAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        
        $productCount        = Product::find()->notDeleted()->count();
        $memberCount         = Customer::find()->notDeleted()->count();
        $staffCount          = User::find()->notDeleted()->count();
        $categoryCount       = ProductCategory::find()
            ->andWhere(['<>', 'id', ProductCategory::ROOT_ID])
            ->notDeleted()->count();
        $productDataProvider = new ArrayDataProvider([
                                                         'allModels' => Product::find()
                                                             ->orderBy(['sold' => SORT_DESC])
                                                             ->limit(5)->all(),
                                                     ]);

        $orders = Order::find();
        $from=0;
        $to=0;

        if ($this->request->get('from')) {
            $from=strtotime($this->request->get('from'));
            $orders = $orders->where(['>=', 'created_at', strtotime("today", $from)]);
        }
        if ($this->request->get('to')) {
            $to=strtotime($this->request->get('to'));
            $orders = $orders->andWhere(['<=', 'created_at',strtotime("tomorrow",strtotime("today",$to))-1 ]);
        }
       
        $items = $orders->all();
        $total = 0;
        foreach ($items as $order) {
            $total += $order->total;
        }

        return $this->render('index', [
            'productCount'    => $productCount,
            'memberCount'     => $memberCount,
            'staffCount'      => $staffCount,
            'categoryCount'   => $categoryCount,
            'productProvider' => $productDataProvider,
            'orders'          => new ArrayDataProvider(['allModels' => $items]),
            'total'           => $total,
            'from'            => $this->request->get('from'),
            'to'              => $this->request->get('to'),
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
