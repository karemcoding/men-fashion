<?php

/**
 * @var $this yii\web\View
 * @var $productCount
 * @var $memberCount
 * @var $staffCount
 * @var $categoryCount
 * @var $productProvider
 */

use backend\models\Product;
use common\widgets\flatpickr\Flatpickr;
use yii\grid\GridView;
use yii\helpers\Html;
use kartik\export\ExportMenu;

$this->title = Yii::t('common', 'Trang chủ');




?>
<div class="row">
    <div class="col-12 col-lg-6 col-xl">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="card-title text-uppercase text-muted mb-2">
                            <?= Yii::t('common', 'Sản phẩm') ?>
                        </h6>
                        <span class="h2 mb-0"><?= $productCount ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-package text-muted mb-0"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 col-xl">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="card-title text-uppercase text-muted mb-2">
                            <?= Yii::t('common', 'Danh mục') ?>
                        </h6>
                        <span class="h2 mb-0"><?= $categoryCount ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-archive text-muted mb-0"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 col-xl">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="card-title text-uppercase text-muted mb-2">
                            <?= Yii::t('common', 'Khách hàng') ?>
                        </h6>
                        <span class="h2 mb-0"><?= $memberCount ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-users text-muted mb-0"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 col-xl">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="card-title text-uppercase text-muted mb-2">
                            <?= Yii::t('common', 'Nhân viên') ?>
                        </h6>
                        <span class="h2 mb-0"><?= $staffCount ?></span>
                    </div>
                    <div class="col-auto">
                        <span class="h2 fe fe-shield text-muted mb-0"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header text-uppercase">
                <h3 class="header-title"><?= Yii::t('common', 'Bán chạy nhất') ?></h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider'     => $productProvider,
                        'tableOptions'     => ['class' => 'table table-bordered'],
                        'headerRowOptions' => ['class' => 'text-center'],
                        'rowOptions'       => ['class' => 'text-center'],
                        'layout'           => "{items}",
                        'columns'          => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'sku',
                            [
                                'attribute' => 'name',
                                'value'     => function (Product $model) {
                                    return Html::a(
                                        $model->name,
                                        ['product/update', 'id' => $model->id]
                                    );
                                },
                                'format'    => 'raw',
                            ],
                            'price:currency',
                            [
                                'attribute' => 'discount_price',
                                'format'    => 'raw',
                                'value'     => function ($model) {
                                    /** @var Product $model */
                                    if ($model->discountObj) {
                                        return Html::a(
                                            Yii::$app->formatter->asCurrency($model->orderPrice()),
                                            [
                                                'discount/update',
                                                'id' => $model->discountObj->discount->id,
                                            ]
                                        );
                                    }

                                    return Yii::$app->formatter->asCurrency($model->orderPrice());
                                },
                            ],
                            [
                                'header' => 'Ảnh đại diện',
                                'value'  => function ($model) {
                                    /** @var Product $model */
                                    if (!$model->viewThumb) {
                                        return null;
                                    }

                                    return Html::tag(
                                        'div',
                                        Html::img(
                                            $model->viewThumb,
                                            ['class' => 'avatar-img rounded']
                                        ),
                                        [
                                            'class' => 'avatar avatar-4by3',
                                        ]
                                    );
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'inventory',
                                'value' => function (Product $model) {
                                    if ($model->inventory > 0) {
                                        return $model->inventory;
                                    }
                                    return Html::tag('span', Yii::t('common', 'HẾT HÀNG'), [
                                        'class' => 'badge bg-danger text-white',
                                    ]);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'header' => 'Đã bán',
                                'value' => 'sold'
                            ]
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header text-uppercase">
                <h3 class="header-title"><?= Yii::t('common', 'Doanh thu') ?></h3>
            </div>
            <div class="card-body">
                <form>
                    <div class="row mb-3">

                        <div class="input-group col-md-3 mb-3">

                            <?= Flatpickr::widget([
                                'name'    => 'from',
                                'options' => [
                                    'class'       => 'form-control',
                                    'placeholder' => 'Từ ngày',
                                    'dateFormat' => 'd/m/Y'
                                ],
                                'value'   => $from,
                            ]) ?>
                        </div>
                        <div class="input-group col-md-3 mb-3">
                            <?= Flatpickr::widget([
                                'name'    => 'to',
                                'options' => [
                                    'class'       => 'form-control',
                                    'placeholder' => 'Đến ngày',
                                ],
                                'clientOptions' => [
                                    'maxDate' => 'today',
                                ],
                                'value'   => $to,

                            ]) ?>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-white btn-main button-search" href="#" type="submit">
                                <i class="fe fe-search"></i>
                            </button>
                        </div>

                    </div>
                    <h2>Doanh thu: <?= Yii::$app->formatter->asCurrency($total) ?></h2>
                </form>
                <div class="table-responsive">
                    <?= ExportMenu::widget([
                        'dataProvider' => $orders,
                        'dropdownOptions' => [
                            'label' => 'Xuất file',
                            'class' => 'btn btn-outline-secondary btn-default'
                        ],
                        'showConfirmAlert' => false,
                        'columns'          => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'number',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a($model->number, ['order/view', 'id' => $model->id]);
                                },
                            ],
                            'total',
                            'created_at:datetime',


                        ],
                        'exportConfig' => [
                            ExportMenu::FORMAT_HTML => false,
                            ExportMenu::FORMAT_TEXT => false,
                            
                        ],
                        'clearBuffers' => true
                        ,'panel'=>[
                            'before' => `$total`,
                        ],
                    ]); ?>
                    <?= GridView::widget([

                        'dataProvider'     => $orders,
                        'tableOptions'     => ['class' => 'table table-bordered'],
                        'headerRowOptions' => ['class' => 'text-center'],
                        'rowOptions'       => ['class' => 'text-center'],
                        'layout'           => "{items}",
                        'columns'          => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'number',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a($model->number, ['order/view', 'id' => $model->id]);
                                },
                            ],
                            'total:currency',
                            'created_at:datetime'

                        ],
                    ]); ?>
                </div>

            </div>
        </div>
    </div>
</div>