<?php

use backend\models\DynamicOrder;
use common\models\Order;
use common\widgets\flatpickr\Flatpickr;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model DynamicOrder */

$this->title = Yii::t('common', 'Add Order');
?>

    <div class="card">
        <div class="card-header">
            <?= $this->render('//widgets/_header', [
                'link_btn' => Html::a(Yii::t('common', "<i class='fe fe-arrow-left mr-1'></i>" . 'Quay lại'),
                    ['index'], ['class' => 'btn btn-secondary']),
                'overview' => NULL,
            ]) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'id' => 'orderForm',
            ]); ?>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'delivery_date')->widget(Flatpickr::class, [
                                'clientOptions' => [
                                    'minDate' => 'today',
                                    'dateFormat' => 'd/m/Y',
                                ],
                            ]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->beginField($model, 'customer', ['options' => [
                                'class' => 'required form-group',
                            ]]) ?>
                            <?= Html::activeLabel($model, 'customer') ?>
                            <div class="d-flex">
                                <div class="w-100">
                                    <?= Html::activeTextInput($model, 'customer', [
                                        'class' => 'form-control',
                                        'readonly' => TRUE,
                                    ]) ?>
                                    <?= Html::error($model, 'customer', ['class' => 'invalid-feedback']) ?>
                                </div>
                                <div>
                                    <?= Html::button("<i class='fe fe-file-plus'></i>",
                                        [
                                            'class' => 'btn btn-primary ml-2',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                            'data-header' => Yii::t('common', 'Customer'),
                                            'href' => Url::to(['order/customer-list']),
                                        ]) ?>
                                </div>
                            </div>
                            <?= $form->endField() ?>
                            <?= Html::activeHiddenInput($model, 'customer_id') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'receiver') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'receiver_tel') ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'delivery_address') ?>
                        </div>
                        <!-- <div class="col-md-4">
                            <?= $form->field($model, 'shipping_method')
                                ->widget(Select2::class, [
                                    'items' => DynamicOrder::selectShippingMethod(),
                                ]) ?>
                        </div> -->
                        <div class="col-md-4">
                            <?= $form->field($model, 'express_company_id')->widget(Select2::class,
                                [
                                    'items' => DynamicOrder::selectExpress(),
                                    'options' => [
                                        'prompt' => Yii::t('common', 'Select One'),
                                    ],
                                ]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'payment_status')->widget(Select2::class, [
                                'clientOptions' => [
                                    'data' => Order::paymentStatusForSelect($model->payment_status),
                                    'escapeMarkup' => new JsExpression("function(m){return m;}"),
                                    'templateResult' => new JsExpression("function(data){return data.html;}"),
                                ],
                            ]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'payment_method')->widget(Select2::class, [
                                'clientOptions' => [
                                    'data' => Order::paymentMethodForSelect($model->payment_status),
                                    'escapeMarkup' => new JsExpression("function(m){return m;}"),
                                    'templateResult' => new JsExpression("function(data){return data.html;}"),
                                ],
                            ]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'remark')->textarea(['rows' => 3]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?= $this->render('_product_card', ['model' => $model]) ?>

            <?= $this->render('_fee_card', ['model' => $model]) ?>

            <?= $this->render('_coupon_card', ['model' => $model]) ?>

            <div class="form-group text-right">
                <?= Html::a(Yii::t('common', 'Hủy'),
                    ['order/create'],
                    ['class' => 'btn btn-secondary mr-1']) ?>
                <?= Html::submitButton(Yii::t('common', 'Next'),
                    [
                        'class' => 'btn btn-primary',
                        'data-dismiss' => 'modal',
                        'name' => 'submitBtn',
                        'value' => 'orderCreate',
                    ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ajaxModalTitle"></h4>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
<?php
$urlPro = Url::to(['order/set-quantity']);
$urlFee = Url::to(['order/set-fee-value']);
$nameId = Html::getInputId($model, 'customer');
$idId = Html::getInputId($model, 'customer_id');
$addressId = Html::getInputId($model, 'delivery_address');
$receiverId = Html::getInputId($model, 'receiver');
$receiverTelId = Html::getInputId($model, 'receiver_tel');
$js = <<<JS
    $(document).on('pjax:complete','#orderFormSelectProductPjaxContainer, #orderFormSelectFeePjaxContainer, #orderFormSelectCouponPjaxContainer', function() {
      $('#ajaxModal').modal('hide');
    });
    $(document).on('change','.quantity__ipt',function(event) {
        $.ajax({
            type: "POST",
            url: "{$urlPro}",
            dataType: "json",
            data: {product: $(this).data('id'), quantity: $(this).val()}
        })
    })
    $(document).on('click','.customer__driver',function(event) {
        event.preventDefault();
        $("#{$nameId}").val($(this).data('email'));
        $("#{$idId}").val($(this).data('id'));
        $("#{$addressId}").val($(this).data('address'));
        $("#{$receiverId}").val($(this).data('name'));
        $("#{$receiverTelId}").val($(this).data('tel'));
        $('#ajaxModal').modal('hide');
        return true;
    })
JS;
$this->registerJs($js);