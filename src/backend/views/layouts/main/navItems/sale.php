<?php
/**
 * @var View $this
 */

use yii\helpers\Html;
use yii\web\View;

$sidebar = 'sidebarSales';
$expressCompanyController = 'express-company';
$orderController = 'order';
$feeController = 'fee';
$couponController = 'coupon';
$discountController = 'discount';
$returnsController = 'order-returns';
$expanded = $this->isController([
    $expressCompanyController,
    $orderController,
    $feeController,
    $couponController,
    $discountController,
    $returnsController,
]);
?>
<li class="nav-item">
    <?= Html::a("<i class='fe fe-dollar-sign'></i> " . Yii::t('common', 'Bán hàng'),
        "#$sidebar",
        [
            'class' => "nav-link",
            'data-toggle' => "collapse",
            'role' => "button",
            'aria-expanded' => $expanded ? 'true' : 'false',
            'aria-controls' => $sidebar,
        ]) ?>
    <?= Html::beginTag('div', [
        'class' => 'collapse' . ($expanded ? ' show' : NULL),
        'id' => $sidebar,
    ]) ?>
    <ul class="nav nav-sm flex-column">
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Đơn hàng'), ["$orderController/index"], [
                'class' => 'nav-link' . $this->activeRoute(
                        [
                            "$orderController/index",
                            "$orderController/create",
                            "$orderController/update",
                            "$orderController/view",
                            "$orderController/preview",
                        ])])
            ?>
        </li>
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Đơn vị vận chuyển'),
                ["$expressCompanyController/index"],
                [
                    'class' => 'nav-link' . ($this->isController($expressCompanyController) ? ' active' : NULL),
                ])
            ?>
        </li>
        <!-- <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Coupon'),
                ["$couponController/index"],
                [
                    'class' => 'nav-link' . ($this->isController($couponController) ? ' active' : NULL),
                ])
            ?>
        </li> -->
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Khuyến mãi'),
                ["$discountController/index"],
                [
                    'class' => 'nav-link' . ($this->isController($discountController) ? ' active' : NULL),
                ])
            ?>
        </li>
        <li class="nav-item">
            <!-- <?= Html::a(Yii::t('common', 'Fee'),
                ["$feeController/index"],
                [
                    'class' => 'nav-link' . ($this->isController($feeController) ? ' active' : NULL),
                ])
            ?>
        </li> -->
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Hoàn trả'),
                ["$returnsController/index"],
                [
                    'class' => 'nav-link' . ($this->isController($returnsController) ? ' active' : NULL),
                ])
            ?>
        </li>
    </ul>
    <?= Html::endTag('div') ?>
</li>