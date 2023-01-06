<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExpressCompany */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="express-company-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'address')->textarea(['maxlength' => true, 'rows' => 4]) ?>
        </div>
    </div>

    <div class="form-group text-right">
        <?= Html::a(Yii::t('common', 'Hủy'),
            ['express-company/index'],
            ['class' => 'btn btn-secondary mr-1']) ?>
        <?= Html::submitButton(Yii::t('common', 'Lưu'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
