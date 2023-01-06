<?php

use common\widgets\columns\ToggleColumn;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Nhóm khách hàng');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a("<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                ['customer-group/create'], [
                    'class' => 'btn btn-primary',
                    'data-toggle' => 'modal',
                    'data-target' => '#ajaxModal',
                    'data-header' => Yii::t('common', 'Thêm nhóm khách hàng')
                ]),
            'overview' => null,
        ]) ?>    </div>
    <div class="card-body">
        <form>
            <div class="row mb-3">
                <div class="input-group col-md-3 mb-3">
                    <?= Html::textInput('name', $filter['name'] ?? NULL,
                        [
                            'class' => 'form-control form-control-prepended height-input',
                            'placeholder' => Yii::t('common', 'Tìm kiếm')
                        ]) ?>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="<?= Url::to(['index']) ?>" class="btn btn-white btn-main mr-1">
                        <span class="fe fe-refresh-cw"></span>
                    </a>
                    <button class="btn btn-main btn-white" type="submit">
                        <i class="fe fe-search"></i>
                    </button>
                </div>
            </div>
        </form>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered'],
            'headerRowOptions' => ['class' => 'text-center'],
            'rowOptions' => ['class' => 'text-center'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                ['header' => 'Trạng thái',
                    'class' => ToggleColumn::class,
                    'url' => Url::to(['customer-group/change-status'])
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Thao tác',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a("<i class='fe fe-edit'></i>", $url,
                                [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                    'data-header' => $model->name
                                ]);
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
<?= $this->render('//widgets/_modal') ?>
