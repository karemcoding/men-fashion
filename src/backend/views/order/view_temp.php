<?php
/**
 * @var View $this
 * @var DynamicOrder $model
 */

use backend\models\DynamicOrder;
use common\models\Fee;
use common\models\Order;
use yii\bootstrap4\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = Yii::t('common', "Preview Invoice");
?>
<?php ActiveForm::begin([
    'id' => 'orderConfirm',
]) ?>
    <div class="card">
        <div class="card-header">
            <div class="form-group text-right mb-0">
                <?= Html::a(Yii::t('common', 'Back'),
                    ['order/create'],
                    [
                        'class' => 'btn btn-info mr-1',
                        'data-confirm' => Yii::t('common', 'Confirm Back to Create Order?'),
                    ]) ?>
                <?= Html::a(Yii::t('common', 'Hủy'),
                    ['order/index'],
                    [
                        'class' => 'btn btn-secondary mr-1',
                        'data-confirm' => Yii::t('common', 'Confirm Cancel Order?'),
                    ]) ?>
                <?= Html::submitButton(Yii::t('common', 'Confirm'),
                    [
                        'class' => 'btn btn-primary',
                        'name' => 'submitBtn',
                        'value' => 'confirm',
                        'data-confirm' => Yii::t('common', 'Confirm Add Order?'),
                    ]) ?>
            </div>
        </div>
        <div class="card-body">
            <div class="p-5 pt-0">
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
                                    <strong>HÓA ĐƠN</strong>
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
                                    <?= Yii::t('common', 'To') ?>
                                </td>
                                <td class="align-text-top pt-1">
                                    :
                                </td>
                                <td class="align-text-top pt-1">
                                    <?= $model->customer_obj->name ?>
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
                                    <?= $model->customer_obj->email ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-text-top pt-1">
                                    <?= Yii::t('common', 'Phone') ?>
                                </td>
                                <td class="align-text-top pt-1">
                                    :
                                </td>
                                <td class="align-text-top pt-1">
                                    <?= $model->customer_obj->phone ?? '-' ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-text-top pt-1">
                                    <?= Yii::t('common', 'Phone') ?>
                                </td>
                                <td class="align-text-top pt-1">
                                    :
                                </td>
                                <td class="align-text-top pt-1">
                                    <?= $model->customer_obj->phone ?? '-' ?>
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
                                    <?= Yii::t('common', 'Ngày vận chuyển') ?>
                                </td>
                                <td class="align-text-top  pt-1">
                                    :
                                </td>
                                <td class="align-text-top  pt-1">
                                    <?= $model->delivery_date ?>
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
                                    <?= Yii::t('common', 'Created Date') ?>
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
                                    <?= Order::generatePaymentStatusView($model->payment_status) ?>
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
                                    <?= Order::generatePaymentMethodView()[$model->payment_method] ?>
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
                        <?php foreach ($model->order_details as $key => $item): ?>
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
                                <strong>Subtotal</strong>
                            </td>
                            <td class="px-0 text-end border-top text-right">
                                <span>
                                    <strong>
                                        <?= Yii::$app->formatter->asCurrency($model->subtotal) ?>
                                    </strong>
                                </span>
                            </td>
                        </tr>
                        <?php foreach ($model->order_coupons as $key => $orderCoupon): ?>
                            <tr>
                                <td class="border-0"></td>
                                <td class="border-0"></td>
                                <td class="border-0"></td>
                                <td class="px-0 text-right <?= $key == 0 ? 'border-top' : 'border-0' ?>">
                                    <strong>
                                        <?= Html::encode($orderCoupon->coupon->name) ?>
                                    </strong>
                                </td>
                                <td class="px-0 text-end text-right <?= $key == 0 ? 'border-top' : 'border-0' ?>">
                                    <span>
                                        <strong> - </strong><?= Yii::$app->formatter->asCurrency($orderCoupon->coupon_value) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($model->order_fees as $key => $orderFee): ?>
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
                        <?php endforeach; ?>
                        <tr>
                            <td class="border-0"></td>
                            <td class="border-0"></td>
                            <td class="border-0"></td>
                            <td class="px-0 text-right border-top">
                                <strong>Total</strong>
                            </td>
                            <td class="px-0 text-end text-right border-top">
                                <span class="h3"><?= Yii::$app->formatter->asCurrency($model->total) ?></span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>