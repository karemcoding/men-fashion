<!-- <?php
/**
 * @var View $this
 */

use backend\widgets\app\AppAsset;
use common\util\AppHelper;
use yii\helpers\Html;
use yii\web\View;

$asset = AppAsset::register($this);

?>
<?= Html::beginTag('button', [
    'class' => 'navbar-toggler',
    'type' => 'button',
    'data-toggle' => 'collapse',
    'data-target' => '#sidebarCollapse',
    'aria-controls' => 'sidebarCollapse',
    'aria-expanded' => 'false',
    'aria-label' => 'Toggle navigation'
]) ?>
<span class='navbar-toggler-icon'></span>
<?= Html::endTag('button') ?>

<?= Html::a(Html::img(AppHelper::logo(),
    [
        'class' => 'navbar-brand-img mx-auto',
        'alt' => '...'
    ]),
    Yii::$app->homeUrl,
    [
        'class' => 'navbar-brand'
    ]) ?> -->
