<?php

use common\models\Message;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Message */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'language')->widget(Select2::class, [
        'items' => Message::language2Select(),
        'options' => ['placeholder' => Yii::t('common', 'Choose language')]
    ]) ?>

    <?= $form->field($model, 'translation')->textarea(['rows' => 6]) ?>

    <div class="form-group text-right">
        <?= Html::a(Yii::t('common', 'Hủy'),
            ['#'],
            ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
        <?= Html::submitButton(Yii::t('common', 'Save'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
