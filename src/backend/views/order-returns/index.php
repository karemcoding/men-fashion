<?php

use common\models\OrderReturns;
use common\widgets\select2\Select2Asset;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Returns');
Select2Asset::register($this);
?>
    <div class="card">
        <div class="card-header">
            <?= $this->render('//widgets/_header', [
                'link_btn' => NULL,
                'overview' => NULL,
            ]) ?>    </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered'],
                'headerRowOptions' => ['class' => 'text-center'],
                'rowOptions' => ['class' => 'text-center'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'order_id',
                        'value' => function ($model) {
                            /** @var OrderReturns $model */
                            return Html::a($model->order->number, ['order/view', 'id' => $model->order->id]);
                        },
                        'format' => 'raw',
                    ],
                    'note:ntext',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            /** @var OrderReturns $model */
                            return OrderReturns::viewStatus($model->status);
                        },
                    ],
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Thao tác',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                /** @var OrderReturns $model */
                                if ($model->status != OrderReturns::STATUS_APPLIED) {
                                    $options = [
                                        'class' => 'btn btn-secondary',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                        'data-header' => Yii::t('common',
                                            $model->order->number . " - " . $model->order->customer->name),
                                    ];
                                    return Html::a("<i class='fe fe-eye'></i>", $url, $options);
                                }
                                return NULL;
                            },
                            'update' => function ($url, $model, $key) {
                                /** @var OrderReturns $model */
                                if ($model->status == OrderReturns::STATUS_APPLIED) {
                                    $options = [
                                        'class' => 'btn btn-primary',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                        'data-header' => Yii::t('common',
                                            $model->order->number . " - " . $model->order->customer->name),
                                    ];
                                    return Html::a("<i class='fe fe-edit'></i>", $url, $options);
                                }
                                return NULL;
                            },
                            'delete' => function ($url, $model, $key) {
                                return NULL;
                            },
                        ],
                    ],
                ],
                'pager' => [
                    'class' => LinkPager::class,
                    'firstPageLabel' => 'First',
                    'lastPageLabel' => 'Last',
                    'maxButtonCount' => 10,
                ],
            ]) ?>
        </div>
    </div>
<?= $this->render('//widgets/_modal') ?>