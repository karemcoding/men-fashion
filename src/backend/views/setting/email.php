<?php
/**
 * @var View $this
 * @var Email $model
 */

use common\models\settings\Email;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('common', 'Setting');
?>

<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header',
            ['link_btn' => NULL, 'overview' => NULL]) ?>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'email_smtp_server')->textInput() ?>
                <?= $form->field($model, 'email_smtp_username')->textInput() ?>
                <?= $form->field($model, 'email_smtp_password')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'email_smtp_protocol')->textInput() ?>
                <?= $form->field($model, 'email_smtp_port')->textInput() ?>
                <?= $form->field($model, 'email_sender')->textInput() ?>
                <?= $form->field($model, 'email_sender_name')->textInput() ?>
            </div>
        </div>

        <div class="form-group text-right">
            <?= Html::submitButton('Save',
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>