<?php
/**
 * @var View $this
 * @var General $model
 */

use common\models\settings\General;
use common\widgets\openstreetmap\OpenStreetMap;
use kartik\file\FileInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = Yii::t('common', 'Setting');
?>

<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header',
            ['link_btn' => null, 'overview' => null]) ?>
    </div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'image')
                    ->widget(FileInput::class, [
                        'pluginOptions' => [
                            'theme' => 'fa',
                            'allowedFileExtensions' => General::EXTENSIONS,
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
                            'showRemove' => FALSE,
                            'browseIcon' => '<i class="fa fa-folder-open-o"></i> ',
                            'maxFileSize' => 1024,
                            'removeClass' => 'btn btn-default btn-secondary fileinput-remove fileinput-remove-button remove__btn',
                            'initialPreview' => [
                                $model->previewLogo()
                            ],
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
            <div class="col-md-4">
                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'tel')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->beginField($model, 'address') ?>
                <?= Html::activeLabel($model, 'address') ?>
                <div class="d-flex">
                    <div class="w-100">
                        <?= Html::activeTextInput($model, 'address', [
                            'class' => 'form-control'
                        ]) ?>
                        <?= Html::error($model, 'address', ['class' => 'invalid-feedback']) ?>
                    </div>
                    <div>
                        <?= Html::button("<i class='fe fe-search'></i>",
                            [
                                'class' => 'btn btn-primary ml-2',
                                'id' => 'searchAddress'
                            ]) ?>
                    </div>
                </div>
                <?= $form->endField() ?>
                <?= $form->field($model, 'lng')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'lat')->hiddenInput()->label(false) ?>
                <?= OpenStreetMap::widget([
                    'id' => 'openStreetMap',
                    'input_lat_id' => Html::getInputId($model, 'lat'),
                    'input_lng_id' => Html::getInputId($model, 'lng'),
                    'input_address_id' => Html::getInputId($model, 'address'),
                    'button_search_id' => 'searchAddress',
                    'url' => Url::to(['site/search-address']),
                    'url_reverse' => Url::to(['site/reverse-latitude']),
                    'lat_val' => $model->lat ?? null,
                    'lng_val' => $model->lng ?? null,
                ]) ?>
            </div>
        </div>

        <div class="form-group text-right">
            <?= Html::submitButton('Save',
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
