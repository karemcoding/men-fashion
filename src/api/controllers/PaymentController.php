<?php

namespace api\controllers;

use api\models\Stripe;
use common\models\Order;
use common\util\PayPalHelper;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;
use yii\web\Response;

/**
 * Class PaymentController
 * @package api\controllers
 */
class PaymentController extends Controller
{
    /**
     * /**
     * @param $token
     * @param $PayerID
     * @return string
     */
    public function actionConfirmPaypal($token, $PayerID)
    {
        $client = PayPalHelper::credentials();
        $request = new OrdersCaptureRequest($token);
        try {
            $response = $client->execute($request);
            if ($response->statusCode >= 201 && $response->statusCode < 300) {
                $orderNumber = $response->result->purchase_units[0]->reference_id;
                if (Order::updateWithPaypal($orderNumber, $token, $PayerID)) {
                    return $this->render('confirm-paypal');
                }
            }
        } catch (HttpException $ex) {
            return $this->render('error');
        }
        return $this->render('error');
    }

    /**
     * @return string
     */
    public function actionFail()
    {
        return $this->render('error');
    }

    /**
     * @return string
     */
    public function actionSuccess()
    {
        return $this->render('confirm-paypal');
    }

    /**
     * @return Response
     */
    public function actionConfirmStripe()
    {
        $paymentIntent = $this->request->get('payment_intent');
        if ($paymentIntent) {
            $stripe = new Stripe();
            if ($stripe->paid($paymentIntent)) {
                return $this->redirect(['payment/success']);
            }
        }
        return $this->redirect(['payment/fail']);
    }
}