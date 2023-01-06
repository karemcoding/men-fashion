<?php

use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Email Template');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => NULL,
            'overview' => NULL,
        ]) ?>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered'],
            'headerRowOptions' => ['class' => 'text-center'],
            'rowOptions' => ['class' => 'text-center'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                'description',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Thao tác',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a("<i class='fe fe-edit'></i>", [
                                'email-content/update',
                                'key' => $key,
                            ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return NULL;
                        },
                        'view' => function ($url, $model, $key) {
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
        ]); ?>
    </div>
</div>