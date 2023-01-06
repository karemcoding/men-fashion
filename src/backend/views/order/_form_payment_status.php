<?php

use common\models\Order;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form-payment-status">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'payment_status')->widget(Select2::class,
        [
            'clientOptions' => [
                'data' => Order::paymentStatusForSelect($model->payment_status),
                'escapeMarkup' => new JsExpression("function(m){return m;}"),
                'templateResult' => new JsExpression("function(data){return data.html;}"),
            ],
        ]) ?>

    <?= $form->field($model, 'payment_method')->widget(Select2::class,
        [
            'clientOptions' => [
                'data' => Order::paymentMethodForSelect($model->payment_method),
                'escapeMarkup' => new JsExpression("function(m){return m;}"),
                'templateResult' => new JsExpression("function(data){return data.html;}"),
            ],
        ]) ?>

    <?= $form->field($model, 'remark')->textarea(['rows' => 5]) ?>

    <div class="form-group text-right">
        <?= Html::a(Yii::t('common', 'Hủy'),
            ['#'],
            ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
        <?= Html::submitButton(Yii::t('common', 'Save'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
