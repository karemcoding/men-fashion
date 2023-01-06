<?php

use common\widgets\ckeditor\CkeditorInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\mailer\EmailContent */

$this->title = Yii::t('common', 'Email Template: {0}', [$model->templateObj->name]);
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(Yii::t('common', "<i class='fe fe-arrow-left mr-1'></i>" . 'Quay lại'),
                ['index'], ['class' => 'btn btn-secondary']),
            'overview' => NULL,
        ]) ?>
    </div>
    <div class="card-body">
        <div class="email-content-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'templateTemp')->textInput([
                'maxlength' => TRUE,
                'readonly' => TRUE,
                'value' => $model->templateObj->name,
            ]) ?>

            <?= $form->field($model, 'subject')->textInput(['maxlength' => TRUE]) ?>

            <?= $form->field($model, 'content')->widget(CkeditorInput::class) ?>

            <div class="form-group text-right">
                <?= Html::a(Yii::t('common', 'Hủy'),
                    ['email-content/index'],
                    ['class' => 'btn btn-secondary mr-1']) ?>
                <?= Html::submitButton(Yii::t('common', 'Save'),
                    ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
