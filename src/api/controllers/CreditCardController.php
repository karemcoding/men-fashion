<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 6:41 PM 5/27/2021
 * @projectName baseProject by ANDY
 */

namespace api\controllers;

use api\models\Customer;
use api\models\Stripe;
use Exception;
use Yii;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class CreditCardController
 * @package backend\controllers
 */
class CreditCardController extends ActiveController
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

    public function actionOne()
    {
        /** @var Customer $user */
        $user = Yii::$app->user->identity;
        if (empty($user->credit_card_ref)) return NULL;
        return (new Stripe())->getCard();
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function actionRequest()
    {
        /** @var Customer $user */
        $user = Yii::$app->user->identity;
        $post = $this->request->post();
        if (!$post) return NULL;
        if (empty($user->credit_card_ref)) {
            return (new Stripe())->addCard($post);
        }
        return (new Stripe())->updateCard($post);
    }

    public function actionRemove()
    {
        return (new Stripe())->removeCard();
    }
}