<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 6:59 PM 5/27/2021
 * @projectName baseProject by ANDY
 */

namespace api\models;

use common\models\Order;
use common\models\settings\Stripe as StripeSetting;
use common\util\AppHelper;
use Exception;
use ReflectionException;
use Stripe\PaymentIntent;
use Stripe\StripeClient;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

class Stripe extends BaseObject
{
    /** @var StripeClient $stripe */
    public $stripe;

    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     */
    public function init()
    {
        /** @var StripeSetting $stripeSetting */
        $stripeSetting = AppHelper::setting()->model(StripeSetting::class);
        $this->stripe = new StripeClient($stripeSetting->secretKey);
        parent::init();
    }

    /**
     * @return array|null
     */
    public function getCard()
    {
        /** @var Customer $user */
        $user = Yii::$app->user->identity;
        try {
            $cardId = $this->stripe->customers->retrieve($user->credit_card_ref)->default_source;
            if (empty($cardId)) {
                return null;
            }
            $card = $this->stripe->customers->retrieveSource($user->credit_card_ref, $cardId);
            return [
                'number' => $card->last4 ?? null,
                'exp_month' => $card->exp_month ?? null,
                'exp_year' => $card->exp_year ?? null,
                'cvc' => '***',
            ];
        } catch (Exception $exception) {
        }
        return null;
    }

    /**
     * @param $post
     * @return array|null
     */
    public function addCard($post)
    {
        /** @var Customer $user */
        $user = Yii::$app->user->identity;
        try {
            if (empty($user->credit_card_ref)) {
                $stripeCus = $this->stripe->customers->create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
                $user->credit_card_ref = $stripeCus->id;
            }
            if ($user->save()) {
                return $this->addCardIntoCustomer($post);
            }
        } catch (Exception $exception) {
        }
        return null;
    }

    /**
     * @param $post
     * @return array|null
     */
    public function updateCard($post)
    {
        /** @var Customer $user */
        $user = Yii::$app->user->identity;
        try {
            $cardId = $this->stripe->customers->retrieve($user->credit_card_ref)->default_source;
            if (empty($cardId)) {
                return $this->addCardIntoCustomer($post);
            }
            $card = $this->stripe->customers->updateSource(
                $user->credit_card_ref,
                $cardId,
                ['exp_month' => $post['exp_month'] ?? null,
                    'exp_year' => $post['exp_year'] ?? null]
            );
            return [
                'number' => $card->last4 ?? null,
                'exp_month' => $card->exp_month ?? null,
                'exp_year' => $card->exp_year ?? null,
                'cvc' => '***',
            ];
        } catch (Exception $exception) {
        }
        return null;
    }

    /**
     * @return null
     */
    public function removeCard()
    {
        /** @var Customer $user */
        $user = Yii::$app->user->identity;
        try {
            $cardId = $this->stripe->customers->retrieve($user->credit_card_ref)->default_source;
            if (!empty($cardId)) {
                $response = $this->stripe->customers->deleteSource($user->credit_card_ref, $cardId);
                return $response->isDeleted();
            }
        } catch (Exception $exception) {
        }
        return null;
    }

    /**
     * @param $post
     * @return array|null
     */
    public function addCardIntoCustomer($post)
    {
        try {
            /** @var Customer $user */
            $user = Yii::$app->user->identity;
            $stripeCard = $this->stripe->tokens->create([
                'card' => [
                    'number' => $post['number'] ?? null,
                    'exp_month' => $post['exp_month'] ?? null,
                    'exp_year' => $post['exp_year'] ?? null,
                    'cvc' => $post['cvc'] ?? null,
                ],
            ]);
            $card = $this->stripe->customers->createSource($user->credit_card_ref, [
                'source' => $stripeCard->id,
            ]);
            return [
                'number' => $card->last4 ?? null,
                'exp_month' => $card->exp_month ?? null,
                'exp_year' => $card->exp_year ?? null,
                'cvc' => '***',
            ];
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * @param Order $order
     * @param string $currency
     * @return mixed|string|null
     */
    public function stripeCharge(Order $order, $currency = 'VND')
    {
        try {
            /** @var Customer $user */
            $user = Yii::$app->user->identity;
            $webRoot = AppHelper::webHostRoot();
            $payment = $this->stripe->paymentIntents->create([
                'amount' => $order->total * 100,
                'currency' => $currency,
                'customer' => $user->credit_card_ref,
                'receipt_email' => $user->email,
                'description' => "Charge for order: {$order->number}",
                'confirm' => true,
                'return_url' => "$webRoot/api/payment/confirm-stripe",
                'metadata' => [
                    'order_number' => $order->number,
                    'order_id' => $order->id,
                ]
            ]);
            $order->payment_ref_id = $payment->id;
            $order->payment_payer_ref_id = $user->credit_card_ref;
            $order->payment_method = Order::METHOD_CARD;

            if ($payment->status == PaymentIntent::STATUS_REQUIRES_ACTION) {
                $order->payment_status = Order::PAYMENT_UNPAID;
                $order->save();
                return $payment->next_action->jsonSerialize()['redirect_to_url']['url'] ?? null;
            }
            if ($payment->status == PaymentIntent::STATUS_SUCCEEDED) {
                $order->payment_status = Order::PAYMENT_PAID;
                $order->save();
                return AppHelper::webHostRoot() . '/api/payment/success';
            }
            return null;
        } catch (Exception $exception) {
        }
        return null;
    }

    /**
     * @param $paymentIntentId
     * @return null
     */
    public function paid($paymentIntentId)
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);
            if ($paymentIntent->status == PaymentIntent::STATUS_SUCCEEDED) {
                $metadata = $paymentIntent->metadata->jsonSerialize();
                Order::updateAll(['payment_status' => Order::PAYMENT_PAID], ['id' => $metadata['order_id']]);
                return true;
            }
        } catch (Exception $exception) {
        }
        return false;
    }
}