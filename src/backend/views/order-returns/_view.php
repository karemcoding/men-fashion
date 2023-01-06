<?php

use common\models\OrderReturns;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderReturns */
/* @var $form yii\widgets\ActiveForm */
/* @var $order_number string */
?>

<div class="order-returns-form">
    <div>
        <h5 class="text-uppercase">
            <?= $model->attributeLabels()['note'] ?>
        </h5>
        <p>
            <?= Html::encode($model->note) ?>
        </p>
        <h5 class="text-uppercase">
            <?= $model->attributeLabels()['status'] ?>:
        </h5>
        <p>
            <?= OrderReturns::viewStatus($model->status) ?>
        </p>
        <h5 class="text-uppercase">
            <?= $model->attributeLabels()['remark'] ?>
        </h5>
        <p>
            <?= Html::encode($model->remark) ?>
        </p>
        <div class="form-group text-right">
            <?= Html::a(Yii::t('common', 'Close'),
                ['order-returns/index'],
                ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>
</div>
