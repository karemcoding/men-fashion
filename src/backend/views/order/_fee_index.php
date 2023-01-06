<?php
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use backend\models\DynamicOrder;
use common\models\Fee;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

?>
<?php ActiveForm::begin([
    'id' => 'selectionFeeForm',
    'action' => Url::to(['order/create'])
]) ?>
<?php Pjax::begin([
    'enablePushState' => false,
    'enableReplaceState' => false,
    'timeout' => 30000,
    'id' => 'feeListPjaxContainer'
]) ?>
<?= GridView::widget([
    'id' => 'feeGrid',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered'],
    'headerRowOptions' => ['class' => 'text-center'],
    'rowOptions' => ['class' => 'text-center'],
    'columns' => [
        [
            'class' => CheckboxColumn::class,
            'header' => Yii::t('common', 'Select'),
            'name' => 'selectionFee',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                $checked = false;
                $session = Yii::$app->session->get(DynamicOrder::FEE_LIST);
                if ($session) {
                    $checked = in_array($model->id, $session);
                }
                return ['checked' => $checked];
            }
        ],
        'name',
        [
            'attribute' => 'value',
            'value' => function ($model) {
                if ($model->type == Fee::TYPE_PERCENT) {
                    return $model->value . "%";
                }
                return Yii::$app->formatter->asCurrency($model->value);
            }
        ],
        [
            'attribute' => 'type',
            'value' => function ($model) {
                return Fee::selectType()[$model->type];
            }
        ],
    ],
    'pager' => [
        'class' => LinkPager::class,
        'firstPageLabel' => 'First',
        'lastPageLabel' => 'Last',
        'maxButtonCount' => 10
    ],
]); ?>
<?php Pjax::end() ?>
<div class="form-group text-right">
    <?= Html::button(Yii::t('common', 'Hủy'),
        ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton(Yii::t('common', 'Save'),
        [
            'class' => 'btn btn-primary',
            'name' => 'submitBtn',
            'value' => 'feeList',
        ]) ?>
</div>
<?php ActiveForm::end() ?>
