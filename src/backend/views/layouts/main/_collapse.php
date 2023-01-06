<?php
/**
 * @var View $this
 */

use backend\util\Permissions;
use yii\web\View;

?>
<div class="collapse navbar-collapse" id="sidebarCollapse">
    <ul class="navbar-nav">
        <?= $this->render('navItems/dashboard') ?>
        <?= $this->render('navItems/product') ?>
        <?= $this->render('navItems/sale') ?>
        <?= $this->render('navItems/warehouse') ?>
        <?= $this->render('navItems/customer') ?>
        <?= $this->render('navItems/notification') ?>
        <?= $this->render('navItems/language') ?>
    </ul>
    <ul class="navbar-nav">
        <?= Yii::$app->user->can(Permissions::USER_INDEX) ? $this->render('navItems/role') : NULL ?>
        <?= Yii::$app->user->can(Permissions::SETTING_MENU_LIST) ? $this->render('navItems/setting') : NULL ?>
    </ul>
</div>
