<?php
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use backend\models\Customer;
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
    'id' => 'customerListPjaxContainer',
]) ?>
<?= GridView::widget([
    'id' => 'customerGrid',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered'],
    'headerRowOptions' => ['class' => 'text-center'],
    'rowOptions' => ['class' => 'text-center'],
    'columns' => [
        [
            'label' => 'Selection',
            'format' => 'raw',
            'value' => function ($model) {
                /** @var Customer $model */
                return Html::button(Yii::t('common', 'Select'), [
                    'class' => 'btn btn-primary customer__driver',
                    'data-id' => $model->id,
                    'data-name' => $model->name . " - " . $model->email,
                ]);
            },
        ],
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'email',
        'phone',
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
