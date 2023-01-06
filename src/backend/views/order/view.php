<?php

/**
 * @var View $this
 * @var Order $model
 * @var $tracking []
 * @var $trackingPaymentStatus []
 */

use backend\widgets\printjs\PrintJs;
use common\models\Fee;
use common\models\Order;
use common\widgets\select2\Select2Asset;
use yii\bootstrap4\BootstrapAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

$bsAsset = BootstrapAsset::register($this);
$select2 = Select2Asset::register($this);
$this->title = Yii::t(
    'common',
    'Invoice: {0} - {1}',
    [
        $model->number, $model->customer->name,
    ]
);
?>
<div class="card text-dark">
    <div class="card-header">
        <div class="row">
            <div class="col-auto">
                <?= Html::a(
                    "<i class='fe fe-arrow-left mr-1'></i>" . Yii::t('common', 'Quay lại'),
                    ['index'],
                    ['class' => 'btn btn-secondary']
                ) ?>
            </div>
            <div class="col-auto ml-auto d-flex">
                <?php if ($model->status != Order::CANCEL) : ?>

                    <?php if (in_array($model->payment_method, [Order::METHOD_CASH, Order::METHOD_BANK])) : ?>
                        <?= Html::a(
                            "<i class='fe fe-dollar-sign'></i>",
                            ['order/update-payment-status', 'id' => $model->id,],
                            [
                                'class' => 'btn btn-info mr-1',
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModal',
                                'data-header' => Yii::t(
                                    'common',
                                    "{$model->number} - {$model->customer->name}"
                                ),
                                'data-original-title' => Yii::t('common', 'Thanh toán'),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'top',
                            ]
                        ); ?>
                    <?php endif; ?>

                    <?php if ($model->status == Order::CREATED && $model->delivery_address != NULL) : ?>
                        <?= Html::a(
                            "<i class='fe fe-truck'></i>",
                            ['order/update', 'id' => $model->id, 'status' => Order::DELIVERY,],
                            [
                                'class' => 'btn btn-info mr-1',
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModal',
                                'data-header' => Yii::t(
                                    'common',
                                    "{$model->number} - {$model->customer->name}"
                                ),
                                'data-original-title' => Yii::t('common', 'Vận chuyển'),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'top',
                            ]
                        ); ?>
                    <?php endif; ?>

                    <?php if ($model->status == Order::DONE) : ?>
                        <?= Html::a(
                            "<i class='fe fe-corner-down-left'></i>",
                            ['order-returns/create', 'order_id' => $model->id],
                            [
                                'class' => 'btn btn-info mr-1',
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModal',
                                'data-header' => Yii::t(
                                    'common',
                                    "{$model->number} - {$model->customer->name}"
                                ),
                                'data-original-title' => Yii::t('common', 'Hoàn trả'),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'top',
                            ]
                        ); ?>
                    <?php endif; ?>

                    <?php if (!in_array($model->status, [Order::ROLLBACK, Order::DONE])) : ?>
                        <?= Html::a(
                            "<i class='fe fe-x-circle'></i>",
                            ['order/update', 'id' => $model->id, 'status' => Order::CANCEL,],
                            [
                                'class' => 'btn btn-danger mr-1',
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModal',
                                'data-header' => Yii::t(
                                    'common',
                                    "{$model->number} - {$model->customer->name}"
                                ),
                                'data-original-title' => Yii::t('common', 'Hủy'),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'top',
                            ]
                        ); ?>
                    <?php endif; ?>

                    <?php if ($model->status == Order::DELIVERY || $model->status == Order::CREATED) : ?>
                        <?= Html::a(
                            "<i class='fe fe-check-circle'></i>",
                            ['order/update', 'id' => $model->id, 'status' => Order::DONE,],
                            [
                                'class' => 'btn btn-success mr-1',
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModal',
                                'data-header' => Yii::t(
                                    'common',
                                    "{$model->number} - {$model->customer->name}"
                                ),
                                'data-original-title' => Yii::t('common', 'Hoàn thành'),
                                'data-bs-toggle' => 'tooltip',
                                'data-bs-placement' => 'top',
                            ]
                        ); ?>
                    <?php endif ?>

                <?php endif; ?>

                <?= PrintJs::widget([
                    'id' => 'printJSBtn',
                    'btnContent' => "<i class='fe fe-printer mr-1'></i> In hóa đơn",
                    'pluginOptions' => [
                        'printable' => 'printJS-invoice',
                        'type' => 'html',
                        'scanStyles' => FALSE,
                        'targetStyles' => ['*'],
                        'targetStyle' => ['*'],
                        'style' => "#printJS-invoice{font:'TimesNewRoman'}",
                        'css' => [$bsAsset->baseUrl . '/css/bootstrap.css'],
                    ],
                    'options' => [
                        'data-original-title' => Yii::t('common', 'In hóa đơn'),
                        'data-bs-toggle' => 'tooltip',
                        'data-bs-placement' => 'top',
                        'class' => 'btn btn-primary',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="card-body p-5" id="printJS-invoice">
        <div class="invoice">
            <div class="row">
                <div class="col text-center">
                    <h2>MAN-FASHION<br>Cửa hàng thời trang nam</h2>
                    <div>
                        Đường 3/2, phường Hưng Lợi, quận Ninh Kiều, thành phố Cần Thơ
                        <br>TEL : 0356 187 392
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <div class="text-center align-middle">
                        <h2>
                            <strong><?= Yii::t('common', 'HÓA ĐƠN') ?></strong>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <table class="float-left" style="width: 400px">
                    <tbody>
                        <tr>
                            <td class="align-text-top pt-1">
                                <?= Yii::t('common', 'Người đặt hàng') ?>
                            </td>
                            <td class="align-text-top pt-1">
                                :
                            </td>
                            <td class="align-text-top pt-1">
                                <?= $model->customer->name ?>
                            </td>
                        </tr>
                        
                        <!-- phone -->
                        <tr>
                            <td class="align-text-top pt-1">
                                <?= Yii::t('common', 'Email') ?>
                            </td>
                            <td class="align-text-top pt-1">
                                :
                            </td>
                            <td class="align-text-top pt-1">
                                <?= $model->customer->email ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-text-top pt-1">
                                <?= Yii::t('common', 'Số điện thoại') ?>
                            </td>
                            <td class="align-text-top pt-1">
                                :
                            </td>
                            <td class="align-text-top pt-1">
                                <?= $model->customer->phone ?? '-' ?>
                            </td>
                        </tr>
                        <tr>
                                <td class="align-text-top pt-1">
                                    <?= Yii::t('common', 'Người nhận') ?>
                                </td>
                                <td class="align-text-top pt-1">
                                    :
                                </td>
                                <td class="align-text-top pt-1">
                                    <?= $model->receiver ?? '-' ?>
                                </td>
                            </tr>
                            <tr>
                            <td class="align-text-top pt-1">
                                <?= Yii::t('common', 'Số điện thoại người nhận') ?>
                            </td>
                            <td class="align-text-top pt-1">
                                :
                            </td>
                            <td class="align-text-top pt-1">
                                <?= $model->receiver_tel ?? '-' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-text-top  pt-1">
                                <?= Yii::t('common', 'Địa chỉ giao hàng') ?>
                            </td>
                            <td class="align-text-top  pt-1">
                                :
                            </td>
                            <td class="align-text-top  pt-1">
                                <?= $model->delivery_address ?? 'Mua tại cửa hàng' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-text-top  pt-1">
                                <?= Yii::t('common', 'Ngày giao hàng') ?>
                            </td>
                            <td class="align-text-top  pt-1">
                                :
                            </td>
                            <td class="align-text-top  pt-1">
                                <?= Yii::$app->formatter->asDate($model->delivery_date) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <table class="float-right">
                    <!-- invoice number -->
                    <tbody>
                        <tr>
                            <td class="align-text-top pt-1">
                                <?= Yii::t('common', 'Số hóa đơn') ?>
                            </td>
                            <td class="align-text-top pt-1">
                                :
                            </td>
                            <td class="align-text-top pt-1">
                                <span class="h3"><?= $model->number ?></span>
                            </td>
                        </tr>
                        <!-- invoice date -->
                        <tr>
                            <td class="align-text-top pt-1">
                                <?= Yii::t('common', 'Ngày tạo') ?>
                            </td>
                            <td class="align-text-top pt-1">
                                :
                            </td>
                            <td class="align-text-top pt-1">
                                <?= Yii::$app->formatter->asDate($model->created_at) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-text-top pt-1">
                                <?= Yii::t('common', 'Trạng thái thanh toán') ?>
                            </td>
                            <td class="align-text-top pt-1">
                                :
                            </td>
                            <td class="align-text-top pt-1">
                                <?= Order::paymentStatusList()[$model->payment_status] ?? Yii::t('common', 'Lỗi') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-text-top pt-1">
                                <?= Yii::t('common', 'Phương thức thanh toán') ?>
                            </td>
                            <td class="align-text-top pt-1">
                                :
                            </td>
                            <td class="align-text-top pt-1">
                                <?= Order::paymentMethodList()[$model->payment_method] ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                            <th class="px-0 bg-transparent border-top-0">
                                <span class="h4"><?= Yii::t('common', 'STT') ?></span>
                            </th>
                            <th class="px-0 bg-transparent border-top-0">
                                <span class="h4"><?= Yii::t('common', 'SẢN PHẨM') ?></span>
                            </th>
                            <th class="px-0 bg-transparent border-top-0">
                                <span class="h4"><?= Yii::t('common', 'KÍCH CỠ') ?></span>
                            </th>
                            <th class="px-0 bg-transparent border-top-0 text-right">
                                <span class="h4"><?= Yii::t('common', 'SỐ LƯỢNG') ?></span>
                            </th>
                            <th class="px-0 bg-transparent border-top-0 text-right">
                                <span class="h4"><?= Yii::t('common', 'ĐƠN VỊ') ?></span>
                            </th>
                            <th class="px-0 bg-transparent border-top-0 text-right">
                                <span class="h4"><?= Yii::t('common', 'TỔNG') ?></span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($model->details as $key => $item): ?>
                            <tr>
                                <td class="px-0">
                                    <?= $key + 1 ?>
                                </td>
                                <td class="px-0">
                                    <?= $item->product->name ?>
                                </td>
                                <td class="px-0">
                                    <?= $item->product->size ?>
                                </td>
                                <td class="px-0 text-right">
                                    <?= $item->quantity ?>
                                </td>
                                <td class="px-0 text-right">
                                    <?= Yii::$app->formatter->asCurrency($item->unit_price) ?>
                                </td>
                                <td class="px-0 text-right">
                                    <?= Yii::$app->formatter->asCurrency($item->amount) ?>
                                </td>
                            </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="px-0 border-top text-right">
                            <strong>Tạm tính</strong>
                        </td>
                        <td class="px-0 text-end border-top text-right">
                            <span>
                                <strong>
                                    <?= Yii::$app->formatter->asCurrency($model->subtotal) ?>
                                </strong>
                            </span>
                        </td>
                    </tr>
                    <?php foreach ($model->orderCoupons as $key => $orderCoupon) : ?>
                        <tr>
                            <td class="border-0"></td>
                            <td class="border-0"></td>
                            <td class="border-0"></td>
                            <td class="px-0 text-right <?= $key == 0 ? 'border-top' : 'border-0' ?>">
                                <strong>
                                    <?= Html::encode($orderCoupon->coupon->name ?? NULL) ?>
                                </strong>
                            </td>
                            <td class="px-0 text-end text-right <?= $key == 0 ? 'border-top' : 'border-0' ?>">
                                <span>
                                    <strong> - </strong><?= Yii::$app->formatter->asCurrency($orderCoupon->coupon_value) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <!-- <?php foreach ($model->orderFees as $key => $orderFee) : ?>
                        <tr>
                            <td class="border-0"></td>
                            <td class="border-0"></td>
                            <td class="border-0"></td>
                            <td class="px-0 text-right <?= $key == 0 ? 'border-top' : 'border-0' ?>">
                                <strong>
                                    <?php
                                    if ($orderFee->fee->type == Fee::TYPE_PERCENT) {
                                        echo $orderFee->fee->name . " ({$orderFee->fee->value}%)";
                                    } else {
                                        echo $orderFee->fee->name;
                                    }
                                    ?>
                                </strong>
                            </td>
                            <td class="px-0 text-end text-right <?= $key == 0 ? 'border-top' : 'border-0' ?>">
                                <span>
                                    <strong> + </strong><?= Yii::$app->formatter->asCurrency($orderFee->fee_value) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?> -->
                    <tr>
                        <td class="border-0"></td>
                        <td class="border-0"></td>
                        <td class="border-0"></td>
                        <td class="px-0 text-right border-top">
                            <strong>Tổng cộng</strong>
                        </td>
                        <td class="px-0 text-right border-top">
                            <span class="h3"><?= Yii::$app->formatter->asCurrency($model->total) ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="header-title"><?= Yii::t('common', 'TRẠNG THÁI') ?></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <?php Pjax::begin([
                'id' => 'orderTrackingGridPjaxContainer',
                'enablePushState' => FALSE,
                'enableReplaceState' => FALSE,
                'timeout' => 30000,
            ]) ?>
            <?= GridView::widget([
                'id' => 'orderTrackingGrid',
                'dataProvider' => $tracking,
                'tableOptions' => ['class' => 'table table-bordered'],
                'headerRowOptions' => ['class' => 'text-center'],
                'rowOptions' => ['class' => 'text-center'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'time',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDatetime($model->time);
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => function ($model) {
                            return Order::generateStatusView()[$model->status];
                        },
                    ],
                    [
                        'attribute' => 'staff',
                        'value' => function ($model) {
                            return $model->staff;
                        },
                    ],
                    [
                        'attribute' => 'remark',
                        'format' => 'ntext',
                        'value' => function ($model) {
                            return $model->remark;
                        },
                    ],
                ],
            ]); ?>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="header-title"><?= Yii::t('common', 'PAYMENT TRACKING') ?></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <?php Pjax::begin([
                'id' => 'orderTrackingPaymentStatusGridPjaxContainer',
                'enablePushState' => FALSE,
                'enableReplaceState' => FALSE,
                'timeout' => 30000,
            ]) ?>
            <?= GridView::widget([
                'id' => 'orderTrackingPaymentStatusGrid',
                'dataProvider' => $trackingPaymentStatus,
                'tableOptions' => ['class' => 'table table-bordered'],
                'headerRowOptions' => ['class' => 'text-center'],
                'rowOptions' => ['class' => 'text-center'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'time',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDatetime($model->time);
                        },
                    ],
                    [
                        'attribute' => 'payment_status',
                        'format' => 'html',
                        'value' => function ($audit) use ($model) {
                            return $audit->payment_status
                                ? Order::generatePaymentStatusView($audit->payment_status)
                                : NULL;
                        },
                    ],
                    [
                        'attribute' => 'payment_method',
                        'format' => 'html',
                        'value' => function ($audit) use ($model) {
                            return $audit->payment_method
                                ? Order::generatePaymentMethodView()[$audit->payment_method]
                                : NULL;
                        },
                    ],
                    [
                        'attribute' => 'staff',
                        'value' => function ($model) {
                            return $model->staff;
                        },
                    ],
                    [
                        'attribute' => 'remark',
                        'format' => 'ntext',
                        'value' => function ($model) {
                            return $model->remark;
                        },
                    ],
                ],
            ]); ?>
            <?php Pjax::end() ?>
            
        </div>
    </div>
</div>
<?= $this->render('//widgets/_modal') ?>