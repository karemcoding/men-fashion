<?php

use common\models\ProductCategory;
use common\util\Status;
use common\widgets\select2\Select2;
use common\widgets\toggle\ToggleInput;
use kartik\file\FileInput;
use yii\bootstrap4\ActiveField;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * @var $this yii\web\View
 * @var $model common\models\ProductCategory
 * @var $form yii\bootstrap4\ActiveForm
 */
?>

    <div class="product-category-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'parent')->widget(Select2::class,
                    [
                        'clientOptions' => [
                            'data' => ProductCategory::list2Select($model->parent),
                            'escapeMarkup' => new JsExpression("function(m){return m;}"),
                            'templateResult' => new JsExpression("function(data){return data.html;}"),
                        ],
                        'options' => [
                            'disabled' => !$model->isNewRecord
                        ]
                    ]) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'status', [
                    'class' => '\yii\bootstrap4\ActiveField',
                    'enableClientValidation' => false,
                ])->widget(
                    ToggleInput::class,
                    [
                        'active_value' => Status::STATUS_ACTIVE,
                        'checked' => ($model->isNewRecord || $model->status == Status::STATUS_ACTIVE),
                    ]
                ) ?>

                <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            </div>
            <div class="col-6">
                <?= Html::activeHiddenInput($model, 'thumbnail') ?>

                <?= $form->field($model, 'image', [
                    'class' => ActiveField::class,
                ])->widget(FileInput::class, [
                    # https://plugins.krajee.com/file-input/plugin-options
                    'pluginOptions' => [
                        'theme' => 'fa',
                        'allowedFileExtensions' => ProductCategory::EXTENSIONS,
                        'uploadClass' => 'btn btn-default btn-success',
                        'layoutTemplates' => [
                            'close' => '<button type="button" id="xBtn" class="close fileinput-remove text-danger bhc__close_file-input" aria-label="Close">
                                <i class="fa fa-close"></i>
                            </button>',
                            'main2' => "{preview}\n<div class='d-flex'><div class='ml-auto'>{remove}\n{upload}\n{browse}</div></div>"
                        ],
                        'showCaption' => FALSE,
                        'showBrowse' => TRUE,
                        'browseLabel' => 'Tải lên',
                        'removeLabel' => 'Xóa',
                        'showClose' => FALSE,
                        'showUpload' => FALSE,
                        'showRemove' => TRUE,
                        'browseIcon' => '<i class="fa fa-folder-open-o"></i> ',
                        'maxFileSize' => 1024,
                        'initialPreview' => [
                            $model->previewThumbnail('path')
                        ],
                        'initialPreviewConfig' => [
                            [
                                'caption' => $model->previewThumbnail('caption'),
                                'size' => $model->previewThumbnail('size')
                            ],
                        ],
                        'initialPreviewShowDelete' => false,
                        'initialPreviewAsData' => true,
                        'previewFileType' => 'image',
                        'removeClass' => 'btn btn-default btn-secondary fileinput-remove fileinput-remove-button remove__btn',
                        'fileActionSettings' => [
                            'showDrag' => false,
                        ],
                        'msgErrorClass' => "alert alert-danger alert-dismissible fade show kv-fileinput-error file-error-message",
                    ],
                    'options' => [
                        'accept' => 'image/*'
                    ],
                ]) ?>
            </div>
        </div>
        <div class="form-group text-right">
            <?= Html::a(Yii::t('common', 'Hủy'),
                ['product-category/index'],
                ['class' => 'btn btn-secondary mr-1']) ?>
            <?= Html::submitButton(Yii::t('common', 'Save'),
                ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$thumbnailId = Html::getInputId($model, 'thumbnail');
$js = <<<JS
    $('.remove__btn').on('click',function (e){
        $("#{$thumbnailId}").val('');
    });
JS;

$this->registerJs($js);