<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 12:05 AM 6/20/2021
 * @projectName baseProject by ANDY
 *
 * @var FreeOneFeeCoupon $model
 */

use common\models\coupons\FreeOneFeeCoupon;
use common\widgets\flatpickr\Flatpickr;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = Yii::t('common', 'Add Coupon');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(Yii::t('common', "<i class='fe fe-arrow-left mr-1'></i>" . 'Quay lại danh sách'),
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
                    <?= $form->field($model, 'value')->textInput(['maxlength' => TRUE]) ?>
                </div>
                <div class="col-md-6">
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
                <?= Html::submitButton(Yii::t('common', 'Lưu'),
                    ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>