<?php
/**
 * @var View $this
 */

use backend\util\Permissions;
use yii\helpers\Html;
use yii\web\View;

$sidebar = 'sidebarRoles';
$roleController = 'role';
$userController = 'user';
$expanded = $this->isController([
    $roleController,
    $userController
]);
?>
<li class="nav-item">
    <?= Html::a("<i class='fe fe-user'></i> " . Yii::t('common', 'Nhân viên'),
        "#$sidebar",
        [
            'class' => "nav-link",
            'data-toggle' => "collapse",
            'role' => "button",
            'aria-expanded' => ($expanded ? 'true' : 'false'),
            'aria-controls' => $sidebar
        ]) ?>
    <?= Html::beginTag('div', [
        'class' => 'collapse' . ($expanded ? ' show' : NULL),
        'id' => $sidebar
    ]) ?>
    <ul class="nav nav-sm flex-column">
        <?php if (Yii::$app->user->can(Permissions::USER_INDEX)): ?>
            <li class="nav-item">
                <?= Html::a(Yii::t('common', 'Nhân viên'), ["$userController/index"], [
                    'class' => 'nav-link' . ($this->isController('user') ? ' active' : NULL)]) ?>
            </li>
        <?php endif; ?>
        <?php if (Yii::$app->user->can(Permissions::ROLE_INDEX)): ?>
            <li class="nav-item">
                <?= Html::a(Yii::t('common', 'Nhóm nhân viên'), ["$roleController/index"], [
                    'class' => 'nav-link' . $this->activeRoute(['role/index', 'role/create', 'role/update'])]) ?>
            </li>
        <?php endif; ?>
        <!-- <?php if (Yii::$app->user->can(Permissions::ROLE_ACCESS)): ?>
            <li class="nav-item">
                <?= Html::a(Yii::t('common', 'Access Control'), ['role/permission'], [
                    'class' => 'nav-link' . $this->activeRoute('role/permission')]) ?>
            </li>
        <?php endif; ?> -->
    </ul>
    <?= Html::endTag('div') ?>
</li>
