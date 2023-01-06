<?php
/**
 * @var View $this
 * @var Stripe $model
 */

use common\models\settings\Stripe;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('common', 'Stripe');
?>

<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header',
            ['link_btn' => null, 'overview' => null]) ?>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'publishableKey')->textInput() ?>

        <?= $form->field($model, 'secretKey')->textInput() ?>

        <div class="form-group text-right">
            <?= Html::submitButton(Yii::t('common', 'Save'),
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>