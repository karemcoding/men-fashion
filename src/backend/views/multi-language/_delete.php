<?php
/**
 * @var View $this
 * @var Message $model
 * @var integer $id
 */

use common\models\Message;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

?>
<div class="message-form">

    <?php $form = ActiveForm::begin(['id' => 'delete__form']); ?>

    <h4>Do you want to delete this item?</h4>

    <?= Html::hiddenInput('id', $id) ?>

    <div class="form-group text-right">
        <?= Html::a(Yii::t('common', 'Hủy'),
            ['#'],
            ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
        <?= Html::submitButton(Yii::t('common', 'Save'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
