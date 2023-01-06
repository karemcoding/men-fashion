<?php
/**
 * @var View $this
 * @var integer $id
 * @var integer $discountId
 */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

?>
<div class="message-form">

    <?php $form = ActiveForm::begin([
        'id' => 'delete__form',
        'action' => Url::to(['discount/product-discount', 'discount' => $discountId]),
    ]); ?>

    <h4>Do you want to delete this item?</h4>

    <?= Html::hiddenInput('product_discount_id', $id) ?>

    <div class="form-group text-right">
        <?= Html::a(Yii::t('common', 'Hủy'),
            ['#'],
            ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
        <?= Html::submitButton(Yii::t('common', 'Xác nhận'),
            [
                'class' => 'btn btn-primary',
                'name' => 'submit',
                'value' => 'DELETE',
            ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
