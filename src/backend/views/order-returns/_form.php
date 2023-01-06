<?php

use common\models\OrderReturns;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\OrderReturns */
/* @var $form yii\widgets\ActiveForm */
/* @var $order_number string */
?>

<div class="order-returns-form">

    <?php $form = ActiveForm::begin(['id' => 'orderReturn__form']); ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'note')
            ->textarea(['rows' => 6,]) ?>
    <?php endif; ?>

    <?php if (!$model->isNewRecord): ?>
        <h6 class="text-uppercase">
            <?= $model->attributeLabels()['note'] ?>
        </h6>
        <p>
            <?= Html::encode($model->note) ?>
        </p>
        <?= $form->field($model, 'status')->widget(Select2::class, [
            'clientOptions' => [
                'data' => OrderReturns::statusForSelect($model->status),
                'escapeMarkup' => new JsExpression("function(m){return m;}"),
                'templateResult' => new JsExpression("function(data){return data.html;}"),
            ],
            'options' => [
                'prompt' => Yii::t('common', 'Select One'),
            ],
        ]) ?>

        <?= $form->field($model, 'remark')
            ->textarea([
                'rows' => 6,
            ]) ?>
    <?php endif; ?>

    <div class="form-group text-right">
        <?= Html::a(Yii::t('common', 'Hủy'),
            ['order-returns/index'],
            ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
        <?= Html::submitButton(Yii::t('common', 'Save'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
