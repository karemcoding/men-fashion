<?php

use backend\widgets\field\AppField;
use common\models\ProductBrand;
use common\models\ProductCategory;
use common\models\ProductSupplier;
use common\util\Status;
use common\widgets\ckeditor\CkeditorInput;
use common\widgets\select2\Select2;
use common\widgets\toggle\ToggleInput;
use kartik\file\FileInput;
use yii\bootstrap4\ActiveField;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="product-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'category_id')->widget(Select2::class,
                    [
                        'clientOptions' => [
                            'data' => ProductCategory::build2Select($model->category_id),
                            'escapeMarkup' => new JsExpression("function(m){return m;}"),
                            'templateResult' => new JsExpression("function(data){return data.html;}"),
                        ],
                        'options' => [
                            'prompt' => Yii::t('common', 'Chọn danh mục'),
                        ],
                    ]) ?>

                <?= $form->field($model, 'sku')->textInput(['maxlength' => TRUE]) ?>

                <?= $form->field($model, 'parent_id')->textInput(['maxlength' => TRUE]) ?>

                <?= $form->field($model, 'size')->textInput(['maxlength' => TRUE]) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => TRUE]) ?>

                <?= $form->field($model, 'status', [
                    'class' => '\yii\bootstrap4\ActiveField',
                    'enableClientValidation' => FALSE,
                ])->widget(
                    ToggleInput::class,
                    [
                        'active_value' => Status::STATUS_ACTIVE,
                        'checked' => ($model->isNewRecord || $model->status == Status::STATUS_ACTIVE),
                    ]
                ) ?>

                <?= $form->field($model, 'price', [
                    'class' => AppField::class,
                    'icon' => "<span class='fe fe-dollar-sign'></span>",
                    'inputOptions' => ['class' => 'form-control form-control-prepended'],
                ])->textInput() ?>

                <?= $form->field($model, 'hot', [
                    'class' => '\yii\bootstrap4\ActiveField',
                    'enableClientValidation' => FALSE,
                ])->widget(
                    ToggleInput::class,
                    [
                        'active_value' => Status::STATUS_ACTIVE,
                        'checked' => $model->hot == Status::STATUS_ACTIVE,
                    ]
                ) ?>

            </div>
            <div class="col-md-6">

                <!-- <?= $form->field($model, 'brand_id')->widget(Select2::class,
                    ['items' => ProductBrand::select2()]) ?> -->

                <?= $form->field($model, 'supplier_id')->widget(Select2::class,
                    ['items' => ProductSupplier::select2()]) ?>

                <?= $form->field($model, 'image')
                    ->widget(FileInput::class, [
                        # https://plugins.krajee.com/file-input/plugin-options
                        'pluginOptions' => [
                            'theme' => 'fa',
                            'allowedFileExtensions' => ProductCategory::EXTENSIONS,
                            'uploadClass' => 'btn btn-default btn-success',
                            'layoutTemplates' => [
                                'main2' => "{preview}\n<div class='d-flex'><div class='ml-auto'>{remove}\n{upload}\n{browse}</div></div>",
                            ],
                            'showCaption' => FALSE,
                            'browseLabel' => 'Tải lên',
                            'showBrowse' => TRUE,
                            'showClose' => FALSE,
                            'showUpload' => FALSE,
                            'showRemove' => TRUE,
                            'removeLabel' => 'Xóa',
                            'browseIcon' => '<i class="fa fa-folder-open-o"></i> ',
                            'maxFileSize' => 1024,
                            'removeClass' => 'btn btn-default btn-secondary fileinput-remove fileinput-remove-button remove__btn__thumbnail',
                            'initialPreview' => [
                                $model->previewThumbnail('path'),
                            ],
                            'initialPreviewConfig' => [
                                [
                                    'caption' => $model->previewThumbnail('caption'),
                                    'size' => $model->previewThumbnail('size'),
                                ],
                            ],
                            'initialPreviewShowDelete' => FALSE,
                            'initialPreviewAsData' => TRUE,
                            'fileActionSettings' => [
                                'showDrag' => FALSE,
                            ],
                            'msgErrorClass' => "alert alert-danger alert-dismissible fade show kv-fileinput-error file-error-message",
                        ],
                        'options' => [
                            'accept' => 'image/*',
                        ],
                    ]) ?>
            </div>
        </div>

        <?= $form->field($model, 'description')->widget(CkeditorInput::class) ?>

        <?= $form->field($model, 'thumbnail')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'images[]', [
            'class' => ActiveField::class,
        ])->widget(FileInput::class, [
            'pluginOptions' => [
                'theme' => 'fa',
                'allowedFileExtensions' => ProductCategory::EXTENSIONS,
                'uploadClass' => 'btn btn-default btn-success',
                'layoutTemplates' => [
                    'main2' => "{preview}\n<div class='d-flex'><div class='ml-auto'>{remove}\n{upload}\n{browse}</div></div>",
                ],
                'showCaption' => FALSE,
                'browseLabel' => 'Tải lên',
                'showBrowse' => TRUE,
                'showClose' => FALSE,
                'showUpload' => FALSE,
                'showRemove' => TRUE,
                'removeLabel' => 'Xóa',
                'browseIcon' => '<i class="fa fa-folder-open-o"></i> ',
                'maxFileSize' => 1024,
                'initialPreview' => $model->previewGallery(),
                'initialPreviewConfig' => $model->previewGallery(TRUE),
                'initialPreviewShowDelete' => TRUE,
                'initialPreviewAsData' => TRUE,
                'overwriteInitial' => FALSE,
                'maxFileCount' => 6,
                'msgErrorClass' => "alert alert-danger alert-dismissible fade show kv-fileinput-error file-error-message",
                'fileActionSettings' => [
                    'showDrag' => FALSE,
                ],
            ],
            'options' => [
                'multiple' => TRUE,
                'accept' => 'image/*',
            ],
        ]) ?>

        <div class="form-group text-right">
            <?= Html::a(Yii::t('common', 'Hủy'),
                ['product/index'],
                ['class' => 'btn btn-secondary mr-1']) ?>
            <?= Html::submitButton(Yii::t('common', 'Lưu'),
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$thumbnailId = Html::getInputId($model, 'thumbnail');
$js = <<<JS
    $('.remove__btn__thumbnail').on('click',function (e){
        $("#{$thumbnailId}").val('');
    });
JS;

$this->registerJs($js);