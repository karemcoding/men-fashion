<?php
/**
 * @var View $this
 * @var array $dataProvider
 */

use common\models\Order;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

?>
<div class="table-responsive">
    <?php Pjax::begin([
        'enablePushState' => FALSE,
        'enableReplaceState' => FALSE,
        'timeout' => 30000,
        'id' => 'orderListPjaxContainer',
    ]) ?>
    <?= \yii\bootstrap4\Html::beginForm([NULL], 'POST', ['data-pjax' => TRUE, 'id' => 'orderSearchForm']) ?>
    <div class="row mb-3">
        <div class="input-group col-md-3 mb-3">
            <?= Html::textInput('number', $filter['number'] ?? NULL,
                ['class' => 'form-control form-control-prepended height-input',
                    'placeholder' => Yii::t('common', 'Number')]) ?>
        </div>
        <div class="input-group col-md-3 mb-3">
            <?= Html::textInput('customer', $filter['customer'] ?? NULL,
                ['class' => 'form-control form-control-prepended height-input',
                    'placeholder' => Yii::t('common', 'Customer')]) ?>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= Url::to([NULL]) ?>" class="btn btn-white btn-main mr-1">
                <span class="fe fe-refresh-cw"></span>
            </a>

            <button class="btn btn-white btn-main button-search" href="#" type="submit">
                <i class="fe fe-search"></i>
            </button>
        </div>
    </div>
    <?= \yii\bootstrap4\Html::endForm() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-bordered'],
        'headerRowOptions' => ['class' => 'text-center'],
        'rowOptions' => ['class' => 'text-center'],
        'columns' => [
            [
                'label' => 'Selection',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var Order $model */
                    return Html::button(Yii::t('common', 'Select'), [
                        'class' => 'btn btn-primary order__driver',
                        'data-number' => $model->number,
                    ]);
                },
            ],
            ['class' => 'yii\grid\SerialColumn'],
            'number',
            [
                'attribute' => 'customer_name',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var Order $model */
                    return $model->customer->name;
                },
            ],
            'total:currency',
            'created_at:datetime',
        ],
        'pager' => [
            'class' => LinkPager::class,
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
            'maxButtonCount' => 10,
        ],
    ]); ?>
    <?php Pjax::end() ?>
</div>
