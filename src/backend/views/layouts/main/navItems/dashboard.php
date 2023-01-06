<?php
/**
 * @var View $this
 */

use yii\helpers\Html;
use yii\web\View;

$sidebar = 'sidebarDashboards';
$route = 'site/index';
$controller = 'site';
?>
<li class="nav-item">
    <?= Html::a("<i class='fe fe-home'></i> " . Yii::t('common', 'Trang chủ'),
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
            <?= Html::a(Yii::t('common', 'Trang chủ'),
                [$route],
                ['class' => 'nav-link' . $this->activeRoute($route)]) ?>
        </li>
    </ul>
    <?= Html::endTag('div') ?>
</li>
