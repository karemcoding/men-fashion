<?php

use backend\models\Customer;
use common\models\CustomerGroup;
use common\util\Status;
use common\widgets\select2\Select2;
use common\widgets\toggle\ToggleInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Customer */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="customer-form">

    <?php $form = ActiveForm::begin([
        'id' => 'customer_form',
        'enableAjaxValidation' => TRUE,
        'validationUrl' => ['validate', 'id' => $model->id]
    ]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput([
                'maxlength' => true,
                'disabled' => !$model->isNewRecord
            ]) ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'group_id')->widget(Select2::class,
                [
                    'items' => CustomerGroup::states(),
                    'options' => [
                        'prompt' => Yii::t('common', 'Chọn nhóm'),
                    ]
                ])
            ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'confirm_password')->passwordInput() ?>

            <?= $form->field($model, 'status')->widget(
                ToggleInput::class,
                [
                    'active_value' => Status::STATUS_ACTIVE,
                    'checked' => ($model->isNewRecord || $model->status == Status::STATUS_ACTIVE),
                ]) ?>
        </div>
    </div>

    <div class="form-group text-right">
        <?= Html::a(Yii::t('common', 'Hủy'),
            ['customer/index'],
            ['class' => 'btn btn-secondary mr-1']) ?>
        <?= Html::submitButton(Yii::t('common', 'Lưu'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
