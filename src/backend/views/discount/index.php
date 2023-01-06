<?php

use common\models\Discount;
use common\widgets\columns\ToggleColumn;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Khuyến mãi');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a("<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                ['discount/create'], ['class' => 'btn btn-primary']),
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
                    'attribute' => 'default_value',
                    'value' => function ($model) {
                        if ($model->type == Discount::TYPE_PERCENT) {
                            return $model->default_value . '%';
                        }
                        return Yii::$app->formatter->asCurrency($model->default_value);
                    },
                ],
                [
                    'attribute' => 'type',
                    'value' => function ($model) {
                        return Discount::selectType()[$model->type];
                    },
                ],
                ['header' => 'Trạng thái',
                    'class' => ToggleColumn::class,
                    'url' => Url::to(['discount/change-status']),
                ],
                'from:date',
                'to:date',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Thao tác',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return NULL;
                        },
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
                'maxButtonCount' => 10,
            ],
        ]); ?>
    </div>
</div>