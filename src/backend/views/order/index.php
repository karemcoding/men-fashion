<?php

use common\models\Order;
use common\widgets\select2\Select2Asset;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\flatpickr\Flatpickr;
use yii\grid\DataColumn;

Select2Asset::register($this);
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Đơn hàng');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(
                "<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                ['order/create'],
                ['class' => 'btn btn-primary']
            ),
            'overview' => NULL,
        ]) ?>
    </div>
    <div class="card-body">
        <form>
            <div class="row mb-3">
                <div class="input-group col-md-3 mb-3">
                    <?= Html::textInput(
                        'number',
                        $filter['number'] ?? NULL,
                        [
                            'class' => 'form-control form-control-prepended height-input',
                            'placeholder' => Yii::t('common', 'Number')
                        ]
                    ) ?>
                </div>
                <div class="input-group col-md-3 mb-3">
                    <?= Html::textInput(
                        'customer',
                        $filter['customer'] ?? NULL,
                        [
                            'class' => 'form-control form-control-prepended height-input',
                            'placeholder' => Yii::t('common', 'Customer')
                        ]
                    ) ?>
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
        </form>

        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'showFooter' => true,
                'tableOptions' => ['class' => 'table table-bordered'],
                'headerRowOptions' => ['class' => 'text-center'],
                'rowOptions' => ['class' => 'text-center'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'number',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->number, ['order/view', 'id' => $model->id]);
                        },
                    ],
                    [
                        'attribute' => 'customer_name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            /** @var Order $model */
                            return $model->getDisplayCustomer();
                        },
                    ],


                    'delivery_address',

                    'total:currency',

                    [
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => function ($model) {
                            /** @var Order $model */
                            return $model->getDisplayStatus();
                        },
                    ],
                    [
                        'attribute' => 'payment_status',
                        'format' => 'html',
                        'value' => function ($model) {
                            /** @var Order $model */
                            return $model->getDisplayPaymentStatus();
                        },
                    ],
                    // 'payment_ref_id',
                    [
                        'attribute' => 'payment_method',
                        'format' => 'html',
                        'value' => function ($model) {
                            /** @var Order $model */
                            return $model->getDisplayPaymentMethod();
                        },
                    ],
                    'created_at:datetime',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Thao tác',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a("<i class='fe fe-edit'></i>", $url);
                            },
                            'update' => function ($url, $model) {
                                return NULL;
                            },
                            'delete' => function ($url, $model) {
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
</div>
<?= $this->render('//widgets/_modal') ?>