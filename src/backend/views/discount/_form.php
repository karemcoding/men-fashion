<?php

use backend\widgets\field\AppField;
use common\models\Discount;
use common\util\Status;
use common\widgets\flatpickr\Flatpickr;
use common\widgets\select2\Select2;
use common\widgets\toggle\ToggleInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Discount */
/* @var $form yii\widgets\ActiveForm */
$icon = $model->type == Discount::TYPE_PERCENT ? 'percent' : 'dollar-sign';
?>

    <div class="discount-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => TRUE]) ?>

                <?= $form->field($model, 'status')->widget(
                    ToggleInput::class,
                    [
                        'active_value' => Status::STATUS_ACTIVE,
                        'checked' => ($model->isNewRecord || $model->status == Status::STATUS_ACTIVE),
                    ]) ?>

                <?= $form->field($model, 'description')->textarea(['maxlength' => TRUE, 'rows' => '5']) ?>
            </div>
            <div class="col-md-6">
                <?php if ($model->isNewRecord): ?>
                    <?= $form->field($model, 'type')->widget(Select2::class, [
                        'items' => Discount::selectType(),
                    ]) ?>
                <?php else: ?>
                    <?= $form->beginField($model, 'type') ?>
                    <?= Html::activeLabel($model, 'type') ?>
                    <div class="form-control">
                        <?= Discount::selectType()[$model->type] ?>
                    </div>
                    <?= $form->endField() ?>
                <?php endif; ?>

                <?= $form->field($model, 'default_value',
                    [
                        'class' => AppField::class,
                        'icon' => "<span class='fe fe-{$icon}' id='icon-discount-default_value'></span>",
                        'inputOptions' => ['class' => 'form-control form-control-prepended'],
                    ])->textInput() ?>

                <?= $form->field($model, 'from_date', [
                    'class' => AppField::class,
                    'icon' => "<span class='fe fe-calendar'></span>",
                    'inputOptions' => ['class' => 'form-control form-control-prepended'],
                ])->widget(Flatpickr::class,
                    [
                        'clientOptions' => [
                            'minDate' => 'today',
                            'dateFormat' => 'd/m/Y',
                        ],
                    ]) ?>

                <?= $form->field($model, 'to_date', [
                    'class' => AppField::class,
                    'icon' => "<span class='fe fe-calendar'></span>",
                    'inputOptions' => ['class' => 'form-control form-control-prepended'],
                ])->widget(Flatpickr::class,
                    [
                        'clientOptions' => [
                            'minDate' => 'today',
                            'dateFormat' => 'd/m/Y',
                        ],
                    ]) ?>
            </div>
        </div>

        <div class="form-group text-right">
            <?= Html::a(Yii::t('common', 'Hủy'),
                ['discount/index'],
                ['class' => 'btn btn-secondary mr-1']) ?>
            <?= Html::submitButton(Yii::t('common', 'Lưu'),
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$js = <<<JS
    $(document).on('change','#discount-type',function(event) {
        if ($(this).val()==20){
            $('#icon-discount-default_value')
                .removeClass('fe-percent')
                .addClass('fe-dollar-sign');
        }
        if ($(this).val()==30){
            $('#icon-discount-default_value')
                .removeClass('fe-dollar-sign')
                .addClass('fe-percent');
        }
    })
JS;
$this->registerJs($js);
