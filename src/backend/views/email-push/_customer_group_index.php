<?php
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use common\models\CustomerGroup;
use yii\bootstrap4\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

?>
<?php Pjax::begin([
    'enablePushState' => FALSE,
    'enableReplaceState' => FALSE,
    'timeout' => 30000,
    'id' => 'customerGroupListPjaxContainer',
]) ?>
<?= GridView::widget([
    'id' => 'customerGroupGrid',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered'],
    'headerRowOptions' => ['class' => 'text-center'],
    'rowOptions' => ['class' => 'text-center'],
    'columns' => [
        [
            'label' => 'Selection',
            'format' => 'raw',
            'value' => function ($model) {
                /** @var CustomerGroup $model */
                return Html::button(Yii::t('common', 'Select'), [
                    'class' => 'btn btn-primary customer__group__driver',
                    'data-id' => $model->id,
                    'data-name' => $model->name,
                ]);
            },
        ],
        'name',
        'description',
    ],
    'pager' => [
        'class' => LinkPager::class,
        'firstPageLabel' => 'First',
        'lastPageLabel' => 'Last',
        'maxButtonCount' => 10,
    ],
]); ?>
<?php Pjax::end() ?>
<div class="form-group text-right">
    <?= Html::button(Yii::t('common', 'Hủy'),
        ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
</div>
