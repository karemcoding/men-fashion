<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 12:05 AM 6/20/2021
 * @projectName baseProject by ANDY
 *
 * @var FreeOneFeeCoupon $model
 */

use common\models\coupons\FreeOneFeeCoupon;
use common\util\Status;
use common\widgets\flatpickr\Flatpickr;
use common\widgets\select2\Select2;
use common\widgets\toggle\ToggleInput;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = Yii::t('common', 'Add Coupon');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(Yii::t('common', "<i class='fe fe-arrow-left mr-1'></i>" . 'Quay lại'),
                ['coupon/list', 'type' => $model->type], ['class' => 'btn btn-secondary']),
            'overview' => NULL,
        ]) ?>
    </div>
    <div class="card-body">
        <div class="coupon-model-form">

            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => TRUE]) ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

                    <?= $form->field($model, 'status')->widget(
                        ToggleInput::class,
                        [
                            'active_value' => Status::STATUS_ACTIVE,
                            'checked' => ($model->isNewRecord || $model->status == Status::STATUS_ACTIVE),
                        ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'fee_id')
                        ->widget(Select2::class,
                            [
                                'items' => FreeOneFeeCoupon::feeList(),
                            ]) ?>

                    <?= $form->field($model, 'minimum_order_amount')
                        ->textInput(['maxlength' => TRUE, 'type' => 'number', 'step' => 0.1]) ?>

                    <?= $form->field($model, 'from')->widget(Flatpickr::class,
                        [
                            'clientOptions' => [
                                'minDate' => 'today',
                                'dateFormat' => 'd/m/Y',
                            ],
                        ]) ?>

                    <?= $form->field($model, 'to')->widget(Flatpickr::class,
                        [
                            'clientOptions' => [
                                'minDate' => 'today',
                                'dateFormat' => 'd/m/Y',
                            ],
                        ]) ?>
                </div>
            </div>

            <div class="form-group text-right">
                <?= Html::a(Yii::t('common', 'Hủy'),
                    ['coupon/index'],
                    ['class' => 'btn btn-secondary mr-1']) ?>
                <?= Html::submitButton(Yii::t('common', 'Save'),
                    ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>