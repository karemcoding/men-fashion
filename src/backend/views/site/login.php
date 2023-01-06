<?php
/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap4\ActiveForm
 * @var $model LoginForm
 */

use common\models\LoginForm;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('common', 'Login');
?>
<div class="row justify-content-center">
    <div class="col-lg-5 align-self-center">
        <div class="card">
            <div class="card-header">
                <h1 class="card-header-title"><?= $this->title ?></h1>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => TRUE]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">

                    <?= Html::submitButton(Yii::t('common', 'Login'),
                        ['class' => 'btn btn-primary w-100 mb-3', 'name' => 'login-button']) ?>

                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
