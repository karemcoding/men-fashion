<?php

use common\util\Status;
use common\widgets\toggle\ToggleInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductBrand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-brand-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status')->widget(ToggleInput::class, [
                'active_value' => Status::STATUS_ACTIVE,
                'inactive_value' => Status::STATUS_INACTIVE,
                'checked' => ($model->isNewRecord || $model->status == Status::STATUS_ACTIVE),
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'rows' => 6]) ?>
        </div>
    </div>

    <div class="form-group text-right">
        <?= Html::a(Yii::t('common', 'Hủy'),
            ['product-brand/index'],
            ['class' => 'btn btn-secondary mr-1']) ?>
        <?= Html::submitButton(Yii::t('common', 'Save'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
