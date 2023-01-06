<?php
/**
 * @var View $this
 */

use backend\widgets\app\AppAsset;
use yii\helpers\Html;
use yii\web\View;

$asset = AppAsset::register($this);
?>
<div class="navbar-user d-md-none">
    <div class="dropdown">
        <?= Html::beginTag('a', [
            'href' => '#',
            'id' => 'sidebarIcon',
            'class' => 'dropdown-toggle',
            'role' => 'button',
            'data-toggle' => 'dropdown',
            'aria-haspopup' => 'true',
            'aria-expanded' => 'false'
        ]) ?>
        <div class="avatar avatar-sm avatar-online">
            <?= Html::img(Yii::$app->user->identity->viewAvatar(), [
                'class' => 'avatar-img rounded-circle',
                'alt' => '...'
            ]) ?>
        </div>
        <?= Html::endTag('a') ?>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="sidebarIcon">
            <?= Html::a('Logout', ['site/logout'], [
                'class' => 'dropdown-item',
                'data-method' => 'post'
            ]) ?>
        </div>
    </div>
</div>
