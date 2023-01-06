<?php

use backend\widgets\field\AppField;
use common\models\ProductDiscount;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model ProductDiscount
 * @var $form \yii\widgets\ActiveForm
 */
?>

<div class="product__discount__mapping__form">
    <?php $form = ActiveForm::begin([
        'id' => 'productDiscountMappingForm',
        'action' => Url::to(['discount/product-discount', 'discount' => $model->discount->id]),
    ]); ?>

    <?= $form->field($model, 'discount_price',
        [
            'class' => AppField::class,
            'icon' => "<span class='fe fe-dollar-sign' id='icon-discount-default_value'></span>",
            'inputOptions' => ['class' => 'form-control form-control-prepended'],
        ])->textInput() ?>

    <?= Html::hiddenInput('product_discount_id', $model->id) ?>

    <div class="form-group text-right">
        <?= Html::button(Yii::t('common', 'Hủy'),
            ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
        <?= Html::submitButton(Yii::t('common', 'Lưu'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
