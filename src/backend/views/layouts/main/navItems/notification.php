<!-- <?php
/**
 * @var View $this
 */

use yii\helpers\Html;
use yii\web\View;

$sidebar = 'sidebarNotification';
$emailContentController = 'email-content';
$emailPushController = 'email-push';
$expanded = $this->isController([
    $emailContentController,
    $emailPushController,
]);
?>
<li class="nav-item">
    <?= Html::a("<i class='fe fe-bell'></i> " . Yii::t('common', 'Notification'),
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
            <?= Html::a(Yii::t('common', 'Notification Push'),
                ["$emailPushController/index"],
                [
                    'class' => 'nav-link' . $this->activeRoute([
                            "$emailPushController/index",
                        ]),
                ])
            ?>
        </li>
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Notification Template'),
                ["$emailContentController/index"],
                [
                    'class' => 'nav-link' . ($this->isController($emailContentController) ? ' active' : NULL),
                ])
            ?>
        </li>
        <li class="nav-item">
            <?= Html::a(Yii::t('common', 'Notification History'),
                ["$emailPushController/history"],
                [
                    'class' => 'nav-link' . $this->activeRoute(["$emailPushController/history"]),
                ])
            ?>
        </li>
    </ul>
    <?= Html::endTag('div') ?>
</li> -->