<?php
/**
 * @var View $this
 */

use yii\helpers\Html;
use yii\web\View;

$sidebar = 'sidebarWarehouse';
$route = 'warehouse/index';
$controller = 'warehouse';
?>
<li class="nav-item">
    <?= Html::a("<i class='fe fe-database'></i> " . Yii::t('common', 'Kho hàng'),
        "#$sidebar",
        [
            'class' => "nav-link",
            'data-toggle' => "collapse",
            'role' => "button",
            'aria-expanded' => ($this->isController($controller) ? 'true' : 'false'),
            'aria-controls' => $sidebar
        ]) ?>
    <?= Html::beginTag('div', [
        'class' => 'collapse' . ($this->isController($controller) ? ' show' : NULL),
        'id' => $sidebar
    ]) ?>
    <ul class="nav nav-sm flex-column">
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Kho hàng'), [$route], [
                'class' => 'nav-link' . $this->activeRoute(
                        [
                            "$controller/index",
                            "$controller/create",
                            "$controller/update"
                        ])])
            ?>
        </li>
    </ul>
    <?= Html::endTag('div') ?>
</li>