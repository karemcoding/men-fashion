<?php

use backend\widgets\field\AppField;
use common\models\Fee;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Fee */
/* @var $form yii\widgets\ActiveForm */
$icon = $model->type == Fee::TYPE_PERCENT ? 'percent' : 'dollar-sign';

?>

    <div class="fee-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => TRUE]) ?>

                <?= $form->field($model, 'type')->widget(Select2::class, [
                    'items' => Fee::selectType(),
                ]) ?>

                <?= $form->field($model, 'value', [
                    'class' => AppField::class,
                    'icon' => "<span class='fe fe-{$icon}' id='icon-fee-value'></span>",
                    'inputOptions' => ['class' => 'form-control form-control-prepended'],
                ])->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'remark')->textarea(['maxlength' => TRUE, 'rows' => 6]) ?>
            </div>
        </div>

        <div class="form-group text-right">
            <?= Html::a(Yii::t('common', 'Hủy'),
                ['fee/index'],
                ['class' => 'btn btn-secondary mr-1']) ?>
            <?= Html::submitButton(Yii::t('common', 'Save'),
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$js = <<<JS
    $(document).on('change','#fee-type',function(event) {
        if ($(this).val()==20){
            $('#icon-fee-value')
                .removeClass('fe-percent')
                .addClass('fe-dollar-sign');
        }
        if ($(this).val()==30){
            $('#icon-fee-value')
                .removeClass('fe-dollar-sign')
                .addClass('fe-percent');
        }
    })
JS;
$this->registerJs($js);