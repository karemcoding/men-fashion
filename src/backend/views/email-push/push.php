<?php

use common\models\mailer\DynamicPush;
use common\widgets\ckeditor\CkeditorInput;
use common\widgets\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\mailer\DynamicPush */
/* @var $form ActiveForm */
$this->title = Yii::t('common', 'Notification Push');
?>

    <div class="card">
        <div class="card-header">
            <?= $this->render('//widgets/_header', [
                'link_btn' => NULL,
                'overview' => NULL,
            ]) ?>
        </div>
        <div class="card-body">
            <div class="email__push">
                <?php $form = ActiveForm::begin(['id' => 'pushEmailForm']); ?>

                <?= $form->field($model, 'subject') ?>

                <?= $form->field($model, 'body')->widget(CkeditorInput::class) ?>

                <?= $form->field($model, 'receiver')->widget(Select2::class, [
                    'items' => DynamicPush::receiverSelect(),
                    'options' => [
                        'prompt' => Yii::t('common', 'Select One'),
                    ],
                ]) ?>
                <?= Html::activeHiddenInput($model, 'receiver_id') ?>
                <?= Pjax::widget([
                    'enablePushState' => FALSE,
                    'enableReplaceState' => FALSE,
                    'timeout' => 30000,
                    'id' => 'iptPjaxContainer',
                ]) ?>
                <div class="form-group text-right">
                    <?= Html::a(Yii::t('common', 'Hủy'),
                        ['index'],
                        ['class' => 'btn btn-secondary mr-1']) ?>
                    <?= Html::submitButton(Yii::t('common', 'Save'),
                        ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalTitle"
         aria-hidden="true">
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
$receiverIdIpt = Html::getInputId($model, 'receiver');
$urlIpt = Url::to(['email-push/load-ipt']);
$formId = $form->id;
$js = <<<JS
    $("#{$receiverIdIpt}").on('change.yii',function(event) {
        var iptValue = $(this).val();
        $.pjax.reload({
            container:'#iptPjaxContainer',
            push: false,
            replace: false,
            timeout:30000,
            url:"{$urlIpt}?type="+iptValue
        })
    });
    $(document).on('pjax:complete','#iptPjaxContainer', function() {
        $('#{$formId}').yiiActiveForm('add',
            {
                id:'dynamicpush-member',
                name:'member',
                container:'.field-dynamicpush-member',
                input:'#dynamicpush-member',
                error:'.invalid-feedback',
                validate:function (attribute, value, messages, deferred, \$form) {
                    yii.validation.required(value, messages, {message:'_'});
                }
            });
        $('#{$formId}').yiiActiveForm('add',
            {
                id:'dynamicpush-group',
                name:'group',
                container:'.field-dynamicpush-group',
                input:'#dynamicpush-group',
                error:'.invalid-feedback',
                validate:function (attribute, value, messages, deferred, \$form) {
                    yii.validation.required(value, messages, {message:'_'});
                }
            });
    });
    $(document).on('click','.customer__driver',function(event) {
        event.preventDefault();
        $("#dynamicpush-receiver_id").val($(this).data('id'));
        $("#dynamicpush-member").val($(this).data('name'));
        $('#ajaxModal').modal('hide');
        return true;
    });
    $(document).on('click','.customer__group__driver',function(event) {
        event.preventDefault();
        $("#dynamicpush-receiver_id").val($(this).data('id'));
        $("#dynamicpush-group").val($(this).data('name'));
        $('#ajaxModal').modal('hide');
        return true;
    });
JS;
$this->registerJs($js);