<?php

/**
 * @var View $this
 * @var InventoryHistory $model
 * @var Product $product
 */

use common\models\InventoryHistory;
use common\models\InventoryReason;
use common\models\Product;
use common\models\Warehouse;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

$this->title = Yii::t('common', 'Nhật ký tồn kho');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(
                "<i class='fe fe-arrow-left mr-1'></i>" . Yii::t('common', 'Back'),
                ['product/update', 'id' => $product->id],
                ['class' => 'btn btn-secondary']
            ),
            'overview' => NULL,
        ]) ?>
    </div>
    <div class="card-body">
        <div class="inventory__form">

            <?php $form = ActiveForm::begin(['id' => 'inventoryHistoryForm']); ?>
            <div class="row">
                <div class="col-md-6">

                    <?= $form->field($model, 'type')->widget(Select2::class, [
                        'clientOptions' => [
                            'data' => InventoryHistory::typeForSelect($model),
                            'escapeMarkup' => new JsExpression("function(m){return m;}"),
                            'templateResult' => new JsExpression("function(data){return data.html;}"),
                        ],
                    ]) ?>

                    <?= $form->field($model, 'quantity') ?>

                    <?= $form->field($model, 'warehouse_id')->widget(Select2::class, [
                        'items' => Warehouse::select(),
                        'options' => [
                            'placeholder' => Yii::t('common', 'Select Warehouse'),
                        ],
                    ]) ?>

                    

                </div>
                <div class="col-md-6">
                    <?= $form->beginField($model, 'ref') ?>
                    <?= Html::activeLabel($model, 'ref') ?>
                    <div class="d-flex">
                        <div class="w-100" id="refFieldPjaxContainer">
                            <?= Html::activeTextInput($model, 'ref', [
                                'readonly' => TRUE,
                                'class' => 'form-control is-valid',
                            ]) ?>
                            <?= Html::error($model, 'ref', ['class' => 'invalid-feedback']) ?>
                        </div>
                        <div>
                            <?= Html::button(
                                Yii::t('common', 'Order'),
                                [
                                    'class' => 'btn btn-primary ml-2',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxRefModal',
                                    'data-header' => Yii::t('common', 'Order'),
                                    'href' => Url::to(['product/list-oder']),
                                    'id' => 'refDriver',
                                ]
                            ) ?>
                        </div>
                    </div>
                    <?= $form->endField() ?>

                    <?= $form->beginField($model, 'reason_id') ?>
                    <?= Html::activeLabel($model, 'reason_id') ?>
                    <div class="d-flex">
                        <div class="w-100" id="reasonFieldPjaxContainer">
                            <?= $this->render('_reason_field', [
                                'model' => $model,
                                'selections' => InventoryReason::select2(),
                            ]) ?>
                        </div>
                        <div>
                            <?= Html::button(
                                "<i class='fe fe-plus'></i>",
                                [
                                    'class' => 'btn btn-primary ml-2',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                    'data-header' => Yii::t('common', 'Reason'),
                                    'href' => Url::to(['inventory-reason/index']),
                                    'id' => 'reasonDriver',
                                ]
                            ) ?>
                        </div>
                    </div>
                    <?= $form->endField() ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
                </div>
            </div>

            <div class="form-group text-right">
                <?= Html::a(
                    Yii::t('common', 'Hủy'),
                    ['product/update', 'id' => $product->id],
                    ['class' => 'btn btn-secondary']
                ) ?>
                <?= Html::submitButton(
                    Yii::t('common', 'Save'),
                    ['class' => 'btn btn-primary']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
<div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ajaxModalTitle"></h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<div class="modal fade ajax__modal" id="ajaxRefModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ajaxModalTitle"></h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<?php
$url = Url::to(['product/render-reason-field']);
$reasonId = Html::getInputId($model, 'reason_id');
$formId = $form->id;
$js = <<<JS
    $('#ajaxModal').on('hide.bs.modal',function(event) {
        $.pjax.reload({
            container:'#reasonFieldPjaxContainer',
            push: false,
            replace: false,
            timeout:30000,
            url:"{$url}"
        })
    });
    $(document).on('change','#{$reasonId}',function(event) {
        $('#{$formId}').yiiActiveForm('validateAttribute',"{$reasonId}");
    });
    $(document).on('click','.order__driver',function(event) {
        event.preventDefault();
        $('#inventoryhistory-ref').val($(this).data('number'));
        $('#ajaxRefModal').modal('hide');
        return true;
    });
JS;
$this->registerJs($js);
