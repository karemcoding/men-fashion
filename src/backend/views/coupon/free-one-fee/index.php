<?php

use common\widgets\columns\ToggleColumn;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Coupon Free One Fee');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a("<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                ['coupon/free-one-fee'], ['class' => 'btn btn-primary']),
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
                'name',
                [
                    'header' => 'Trạng thái',
                    'class' => ToggleColumn::class,
                    'url' => Url::to(['coupon/change-status']),
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Thao tác',
                    'buttons' => [
                        'view' => function () {
                            return NULL;
                        },
                        'update' => function ($url, $model) {
                            return Html::a("<i class='fe fe-edit'></i>", ['coupon/free-one-fee', 'id' => $model->id]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a("<i class='fe fe-trash'></i>",
                                ['coupon/delete', 'id' => $model->id],
                                [
                                    'data-confirm' => 'Do you want to delete this item?',
                                    'data-method' => 'POST',
                                ]);
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
        ]); ?>
    </div>
</div>