<?php

use backend\models\User;
use common\models\Role;
use common\widgets\select2\Select2;
use common\widgets\toggle\ToggleInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var User $model
 * @var \yii\widgets\ActiveForm $form
 */

?>
<div class="user-form">
    <?php $form = ActiveForm::begin([
        'id' => 'user_form',
        'enableAjaxValidation' => TRUE,
        'validationUrl' => ['validate', 'id' => $model->id]
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name') ?>

            <?= $form->field($model, 'username')->textInput([
                'disabled' => !$model->isNewRecord
            ]) ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'status')->widget(ToggleInput::class, [
                'active_value' => 10,
                'checked' => $model->status
            ]) ?>
        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'tel') ?>

            <?= $form->beginField($model, 'password') ?>
            <?= Html::activeLabel($model, 'password') ?>
            <?= Html::activePasswordInput($model, 'password', [
                'class' => 'form-control'
            ]) ?>
            <?= Html::error($model, 'password', ['class' => 'invalid-feedback']) ?>
            <?= $form->endField() ?>

            <?= $form->field($model, 'confirm_password')->passwordInput() ?>

            <?= $form->field($model, 'role_id')->widget(Select2::class,
                [
                    'items' => Role::buildSelect2(),
                    'options' => [
                        'prompt' => Yii::t('common', 'Select Role'),
                    ]
                ])
            ?>

        </div>
    </div>

    <div class="form-group text-right">
        <?= Html::a(Yii::t('common', 'Hủy'),
            ['user/index'],
            ['class' => 'btn btn-secondary mr-1']) ?>
        <?= Html::submitButton(Yii::t('common', 'Save'),
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
