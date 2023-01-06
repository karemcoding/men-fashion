<?php

use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */
/* @var $data array */

$this->title = Yii::t('common', 'Cập nhật khách hàng: {0}', [$model->name]);
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(Yii::t('common', "<i class='fe fe-arrow-left mr-1'></i>" . 'Quay lại danh sách'),
                ['index'], ['class' => 'btn btn-secondary']),
            'overview' => NULL,
        ]) ?>
    </div>
    <div class="card-body">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="header-title"><?= Yii::t('common', 'Lịch sử đặt hàng') ?></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <?php Pjax::begin([
                'id' => 'cusOrderPjaxContainer',
                'formSelector' => FALSE,
                'enablePushState' => FALSE,
                'enableReplaceState' => FALSE,
                'timeout' => 30000,
            ]) ?>
            <?= GridView::widget([
                'dataProvider' => $data,
                'tableOptions' => ['class' => 'table table-bordered'],
                'headerRowOptions' => ['class' => 'text-center'],
                'rowOptions' => ['class' => 'text-center'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'number',
                        'value' => function ($model) {
                            return Html::a($model->number, ['order/view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'delivery_address',
                    'total:currency',
                    [
                        'attribute' => 'status',
                        'value' => 'displayStatus',
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'payment_status',
                        'value' => 'displayPaymentStatus',
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'payment_method',
                        'value' => 'displayPaymentMethod',
                        'format' => 'html',
                    ],
                    'delivery_date:date',
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
    </div>
</div>