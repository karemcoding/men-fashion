<?php

use common\util\Status;
use common\widgets\toggle\ToggleInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CustomerGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-group-form">

    <?php $form = ActiveForm::begin(['id' => 'memberGroupForm']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status', ['enableClientValidation' => FALSE])
        ->widget(ToggleInput::class, [
            'active_value' => Status::STATUS_ACTIVE,
            'checked' => $model->status || $model->isNewRecord,
        ]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'rows' => 3]) ?>

    <div class="form-group text-right">
        <?= Html::button(Yii::t('common', 'Hủy'),
            ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
        <?= Html::submitButton(Yii::t('common', 'Lưu'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
