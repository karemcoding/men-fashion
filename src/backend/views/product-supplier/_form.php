<?php

use backend\widgets\field\AppField;
use common\util\Status;
use common\widgets\toggle\ToggleInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductSupplier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-supplier-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status')->widget(ToggleInput::class, [
                'active_value' => Status::STATUS_ACTIVE,
                'inactive_value' => Status::STATUS_INACTIVE,
                'checked' => ($model->isNewRecord || $model->status == Status::STATUS_ACTIVE),
            ]) ?>

            <?= $form->field($model, 'tel', [
                'class' => AppField::class,
                'inputOptions' => ['class' => 'form-control form-control-prepended'],
                'icon' => "<span class='fe fe-phone'></span>"
            ])->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'fax', [
                'class' => AppField::class,
                'inputOptions' => ['class' => 'form-control form-control-prepended'],
                'icon' => "<span class='fe fe-printer'></span>"
            ])->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">


            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'rows' => 6]) ?>

        </div>
    </div>
    <div class="form-group text-right">
        <?= Html::a(Yii::t('common', 'Hủy'),
            ['product-supplier/index'],
            ['class' => 'btn btn-secondary mr-1']) ?>
        <?= Html::submitButton(Yii::t('common', 'Save'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
