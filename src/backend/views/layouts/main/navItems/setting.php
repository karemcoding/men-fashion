<!-- <?php
/**
 * @var View $this
 */

use backend\util\Permissions;
use yii\helpers\Html;
use yii\web\View;

$sidebar = 'sidebarSetting';
$controller = 'setting'
?>
<li class="nav-item">
    <?= Html::a("<i class='fe fe-settings'></i> " . Yii::t('common', 'Setting'),
        "#$sidebar",
        [
            'class' => 'nav-link',
            'data-toggle' => 'collapse',
            'role' => 'button',
            'aria-expanded' => ($this->isController($controller) ? 'true' : 'false'),
            'aria-controls' => $sidebar
        ]) ?>
    <?= Html::beginTag('div', [
        'class' => 'collapse' . ($this->isController($controller) ? ' show' : NULL),
        'id' => $sidebar
    ]) ?>
    <ul class="nav nav-sm flex-column">
        <?php if (Yii::$app->user->can(Permissions::SETTING_GENERAL)): ?>
            <li class="nav-item">
                <?= Html::a(Yii::t('common', 'General'), ['setting/general'], [
                    'class' => 'nav-link' . $this->activeRoute('setting/general')]) ?>
            </li>
        <?php endif; ?>
        <?php if (Yii::$app->user->can(Permissions::SETTING_EMAIL)): ?>
            <li class="nav-item">
                <?= Html::a(Yii::t('common', 'Email'), ['setting/email'], [
                    'class' => 'nav-link' . $this->activeRoute('setting/email')]) ?>
            </li>
        <?php endif; ?>
        <?php if (Yii::$app->user->can(Permissions::SETTING_PAYPAL)): ?>
            <li class="nav-item">
                <?= Html::a(Yii::t('common', 'PayPal'), ['setting/paypal'], [
                    'class' => 'nav-link' . $this->activeRoute('setting/paypal')]) ?>
            </li>
        <?php endif; ?>
        <?php if (Yii::$app->user->can(Permissions::SETTING_STRIPE)): ?>
            <li class="nav-item">
                <?= Html::a(Yii::t('common', 'Stripe'), ['setting/stripe'], [
                    'class' => 'nav-link' . $this->activeRoute('setting/stripe')]) ?>
            </li>
        <?php endif; ?>
    </ul>
    <?= Html::endTag('div') ?>
</li> -->
