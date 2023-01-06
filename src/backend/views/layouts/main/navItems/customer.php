<?php
/**
 * @var View $this
 */

use yii\helpers\Html;
use yii\web\View;

$sidebar = 'sidebarCustomer';
$route = 'customer/index';
$customerController = 'customer';
$groupController = 'customer-group';
$expanded = $this->isController([
    $customerController,
    $groupController
]);
?>
<li class="nav-item">
    <?= Html::a("<i class='fe fe-users'></i> " . Yii::t('common', 'Khách hàng'),
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
            <?= Html::a(Yii::t('common', 'Nhóm khách hàng'), ["$groupController/index"],
                [
                    'class' => 'nav-link' . ($this->isController($groupController) ? ' active' : null)
                ])
            ?>
        </li>
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Khách hàng'), ["$customerController/index"],
                [
                    'class' => 'nav-link' . $this->activeRoute(
                            [
                                "$customerController/index",
                                "$customerController/create",
                                "$customerController/update"
                            ])
                ])
            ?>
        </li>
    </ul>
    <?= Html::endTag('div') ?>
</li>