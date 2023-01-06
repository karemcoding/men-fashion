<?php
/**
 * @var View $this
 * @var Profile $model
 */

use backend\models\Profile;
use kartik\file\FileInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('common', 'Thông tin cá nhân')
?>
    <div class="card">
        <div class="card-header">
            <?= $this->render('//widgets/_header', [
                'link_btn' => null,
                'overview' => null,
            ]) ?>
        </div>
        <div class="card-body">
            <div class="profile__form">

                <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-md-4">
                        <?= Html::activeHiddenInput($model, 'avatar') ?>
                        <?= $form->field($model, 'fileAvatar')
                            ->widget(FileInput::class, [
                                # https://plugins.krajee.com/file-input/plugin-options
                                'pluginOptions' => [
                                    'theme' => 'fa',
                                    'allowedFileExtensions' => Profile::EXTENSIONS,
                                    'uploadClass' => 'btn btn-default btn-success',
                                    'layoutTemplates' => [
                                        'main2' => "{preview}\n<div class='d-flex'><div class='ml-auto'>{remove}\n{upload}\n{browse}</div></div>",
                                    ],
                                    'browseLabel' => 'Tải lên',
                                    'browseOnZoneClick' => FALSE,
                                    'showCaption' => FALSE,
                                    'showBrowse' => TRUE,
                                    'showClose' => FALSE,
                                    'showUpload' => FALSE,
                                    'showRemove' => TRUE,
                                    'removeLabel' => 'Xóa',
                                    'browseIcon' => '<i class="fa fa-folder-open-o"></i> ',
                                    'maxFileSize' => 1024,
                                    'removeClass' => 'btn btn-default btn-secondary fileinput-remove fileinput-remove-button remove__btn',
                                    'initialPreview' => [
                                        $model->previewAvatar()
                                    ],
                                    /*
                                    'initialPreviewConfig' => [
                                        [
                                            'caption' => null,
                                            'size' => null
                                        ],
                                    ],
                                    */
                                    'initialPreviewShowDelete' => false,
                                    'initialPreviewAsData' => true,
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
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'role')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                        <?= $form->field($model, 'id')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                        <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'confirmPassword')->passwordInput(['maxlength' => true]) ?>
                    </div>
                </div>

                <div class="form-group text-right">
                    <?= Html::submitButton(Yii::t('common', 'Save'),
                        ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
<?php
$thumbnailId = Html::getInputId($model, 'avatar');
$js = <<<JS
    $('.remove__btn').on('click',function (e){
        $("#{$thumbnailId}").val('');
    });
JS;

$this->registerJs($js);