<!-- <?php
/**
 * @var View $this
 */

use yii\helpers\Html;
use yii\web\View;

$sidebar = 'multiLanguageCustomer';
$route = 'multi-language/index';
$controller = 'multi-language';
?>
<li class="nav-item">
    <?= Html::a("<i class='fe fe-globe'></i> " . Yii::t('common', 'Language'),
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
            <?= Html::a(Yii::t('common', 'Language'), [$route], [
                'class' => 'nav-link' . $this->activeRoute("$controller/index")])
            ?>
        </li>
    </ul>
    <?= Html::endTag('div') ?>
</li> -->