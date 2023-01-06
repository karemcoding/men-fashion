<?php

use backend\models\Customer;
use common\models\CustomerGroup;
use common\util\Status;
use common\widgets\columns\ToggleColumn;
use common\widgets\select2\Select2;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Khách hàng');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a("<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                ['customer/create'], ['class' => 'btn btn-primary']),
            'overview' => null,
        ]) ?>    </div>
    <div class="card-body">
        <form>
            <div class="row mb-3">
                <div class="input-group col-md-3 mb-3">
                    <?= Html::textInput('keyword', $filter['keyword'] ?? NULL,
                        [
                            'class' => 'form-control form-control-prepended height-input',
                            'placeholder' => Yii::t('common', 'Tìm kiếm'),
                        ]) ?>
                </div>
                <div class="input-group col-md-3 mb-3">
                    <?= Select2::widget([
                        'options' => [
                            'prompt' => Yii::t('common', 'Trạng thái'),
                        ],
                        'value' => $filter['state'] ?? NULL,
                        'name' => 'state',
                        'items' => Status::states(),

                    ]) ?>
                </div>
                <div class="input-group col-md-3 mb-3">
                    <?= Select2::widget([
                        'options' => [
                            'prompt' => Yii::t('common', 'Nhóm'),
                        ],
                        'value' => $filter['group'] ?? NULL,
                        'name' => 'group',
                        'items' => CustomerGroup::states(),
                    ]) ?>

                </div>
                <div class="col-md-3 mb-3">
                    <a href="<?= Url::to(['index']) ?>" class="btn btn-white btn-main mr-1">
                        <span class="fe fe-refresh-cw"></span>
                    </a>

                    <button class="btn btn-white btn-main button-search" href="#" type="submit">
                        <i class="fe fe-search"></i>
                    </button>

                </div>
            </div>
        </form>
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered'],
                'headerRowOptions' => ['class' => 'text-center'],
                'rowOptions' => ['class' => 'text-center'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->name ?? $model->id, ['customer/update', 'id' => $model->id]);
                        }
                    ],
                    'email',
                    'phone',
                    [
                        'attribute' => 'group',
                        'value' => function ($model) {
                            /** @var Customer $model */
                            return $model->group->name ?? null;
                        }
                    ],
                    ['header' => 'Trạng thái',
                        'class' => ToggleColumn::class,
                        'url' => Url::to(['customer/change-status']),
                    ],
                    'created_at:datetime',
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
</div>