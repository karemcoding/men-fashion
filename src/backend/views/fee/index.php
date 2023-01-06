<?php

use common\models\Fee;
use common\util\Status;
use common\widgets\columns\ToggleColumn;
use common\widgets\toggle\ToggleInput;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Fee');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a("<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                ['fee/create'], ['class' => 'btn btn-primary']),
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
                    'attribute' => 'value',
                    'value' => function ($model) {
                        if ($model->type == Fee::TYPE_PERCENT) {
                            return $model->value . '%';
                        }
                        return Yii::$app->formatter->asCurrency($model->value);
                    },
                ],
                ['header' => 'Trạng thái',
                    'class' => ToggleColumn::class,
                    'url' => Url::to(['fee/change-status']),
                ],
                [
                    'attribute' => 'shipping_fee',
                    'format' => 'raw',
                    'value' => function ($model) {
                        /** @var $model Fee */
                        if ($model->type == Fee::TYPE_PERCENT) {
                            return NULL;
                        }
                        return ToggleInput::widget([
                            'active_value' => Status::STATUS_ACTIVE,
                            'inactive_value' => Status::STATUS_INACTIVE,
                            'checked' => $model->shipping_fee == Status::STATUS_ACTIVE,
                            'action' => [
                                'url' => Url::to(['fee/change-fee']),
                                'request_type' => 'POST',
                                'sender' => $model->id,
                            ],
                        ]);
                    },
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
                'maxButtonCount' => 10,
            ],
        ]); ?>
    </div>
</div>