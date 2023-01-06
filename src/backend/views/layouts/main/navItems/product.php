<?php
/**
 * @var View $this
 */

use yii\helpers\Html;
use yii\web\View;

$sidebar = 'sidebarProduct';
$route = 'product/index';
$productController = 'product';
$categoryController = 'product-category';
$brandController = 'product-brand';
$supplierController = 'product-supplier';
$expanded = $this->isController([
    $productController,
    $categoryController,
    $brandController,
    $supplierController
]);
?>
<li class="nav-item">
    <?= Html::a("<i class='fe fe-archive'></i> " . Yii::t('common', 'Sản phẩm'),
        "#$sidebar",
        [
            'class' => "nav-link",
            'data-toggle' => "collapse",
            'role' => "button",
            'aria-expanded' => $expanded ? 'true' : 'false',
            'aria-controls' => $sidebar
        ]) ?>
    <?= Html::beginTag('div', [
        'class' => 'collapse' . ($expanded ? ' show' : NULL),
        'id' => $sidebar
    ]) ?>
    <ul class="nav nav-sm flex-column">
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Sản phẩm'), [$route], [
                'class' => 'nav-link' . ($this->isController($productController) ? ' active' : null)])
            ?>
        </li>
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Danh mục'), ["product-category/index"], [
                'class' => 'nav-link' . $this->activeRoute(
                        [
                            "$categoryController/index",
                            "$categoryController/create",
                            "$categoryController/update"
                        ])])
            ?>
        </li>
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Nhà cung cấp'), ["$supplierController/index"], [
                'class' => 'nav-link' . $this->activeRoute(
                        [
                            "$supplierController/index",
                            "$supplierController/create",
                            "$supplierController/update"
                        ])])
            ?>
        </li>
        <!-- <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Brand'), ["$brandController/index"], [
                'class' => 'nav-link' . $this->activeRoute(
                        [
                            "$brandController/index",
                            "$brandController/create",
                            "$brandController/update"
                        ])])
            ?>
        </li> -->
    </ul>
    <?= Html::endTag('div') ?>
</li>