<?php

use common\models\InventoryReason;
use yii\bootstrap4\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model InventoryReason */

?>
<?php Pjax::begin([
    'enablePushState' => false,
    'enableReplaceState' => false,
    'timeout' => 30000,
]) ?>
<div class="card">
    <div class="card-body">

        <?php $form = ActiveForm::begin([
            'id' => 'inventoryReasonForm',
            'options' => [
                'data-pjax' => true
            ],
            'action' => Url::to(['inventory-reason/create'])
        ]); ?>

        <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>

        <div class="form-group text-right">
            <?= Html::button(Yii::t('common', 'Hủy'),
                ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
            <?= Html::submitButton("<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered'],
    'headerRowOptions' => ['class' => 'text-center'],
    'rowOptions' => ['class' => 'text-center'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'reason',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Thao tác',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a("<i class='fe fe-edit'></i>", $url);
                },
                'delete' => function ($url, $model) {
                    return Html::a("<i class='fe fe-trash'></i>",
                        [
                            'inventory-reason/remove',
                            'id' => $model->id
                        ]);
                },
            ],
        ],
    ],
]); ?>
<?php Pjax::end() ?>
