<?php

use common\widgets\columns\ToggleColumn;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Kho hàng');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(Yii::t('common', 'Create'),
                ['warehouse/create'], ['class' => 'btn btn-primary']),
            'overview' => null,
        ]) ?>    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered'],
            'headerRowOptions' => ['class' => 'text-center'],
            'rowOptions' => ['class' => 'text-center'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                'address',
                ['header' => 'Trạng thái',
                    'class' => ToggleColumn::class,
                    'url' => Url::to(['warehouse/change-status'])
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Thao tác',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a("<i class='fe fe-edit'></i>", $url);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a("<i class='fe fe-trash'></i>", $url,
                                [
                                    'data-confirm' => 'Do you want to delete this item?',
                                    'data-method' => 'post',
                                ]);
                        },
                    ],
                ],
            ],
            'pager' => [
                'class' => LinkPager::class,
                'firstPageLabel' => 'First',
                'lastPageLabel' => 'Last',
                'maxButtonCount' => 10
            ],
        ]); ?>
    </div>
</div>