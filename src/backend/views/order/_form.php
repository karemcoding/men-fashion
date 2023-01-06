<?php

use common\models\Order;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->beginField($model, 'status') ?>
    <?= Html::activeLabel($model, 'status') ?>
    <div class="form-control">
        <?= Order::generateStatusView()[$model->status] ?>
    </div>
    <?= $form->endField() ?>

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
