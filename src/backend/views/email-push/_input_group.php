<?php
/**
 * @var DynamicPush $model
 */

use common\models\mailer\DynamicPush;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$form = new ActiveForm();
?>
<?php Pjax::begin([
    'enablePushState' => FALSE,
    'enableReplaceState' => FALSE,
    'timeout' => 30000,
    'id' => 'iptPjaxContainer',
]) ?>
<?= $form->beginField($model, 'group', ['options' => [
    'class' => 'form-group required',
]]) ?>
<?= Html::activeLabel($model, 'group') ?>
<div class="d-flex">
    <div class="w-100">
        <?= Html::activeTextInput($model, 'group', [
            'class' => 'form-control',
            'readonly' => TRUE,
        ]) ?>
    </div>
    <div>
        <?= Html::button("<i class='fe fe-file-plus'></i>",
            [
                'class' => 'btn btn-primary ml-2',
                'data-toggle' => 'modal',
                'data-target' => '#ajaxModal',
                'data-header' => Yii::t('common', 'Customer'),
                'href' => Url::to(['email-push/customer-group-list']),
            ]) ?>
    </div>
</div>
<?= $form->endField() ?>
<?php Pjax::end() ?>
