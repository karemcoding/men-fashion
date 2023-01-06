<?php
/**
 * @var View $this
 */

use common\models\InventoryReason;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var InventoryReason $model
 */
?>
<div class="card">
    <div class="card-body">

        <?php $form = ActiveForm::begin([
            'id' => 'inventoryReasonUpdateForm',
            'options' => [
                'data-pjax' => true
            ],
            'action' => Url::to(['inventory-reason/update', 'id' => $model->id])
        ]); ?>

        <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>

        <div class="form-group text-right">
            <?= Html::button(Yii::t('common', 'Hủy'),
                ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
            <?= Html::submitButton("<i class='fe fe-save mr-1'></i>" . Yii::t('common', 'Save'),
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
