<!-- <?php
/**
 * @var DynamicOrder $model
 */

use backend\models\DynamicOrder;
use yii\helpers\Html;
use yii\widgets\Pjax;

?>
<div class="card">
    <div class="card-header">
        <div class="row align-items-end">
            <div class="col">
                <h3 class="header-title">
                    <?= Yii::t('common', 'Fees') ?>
                </h3>
            </div>
            <div class="col-auto">
                <?= Html::a(
                    Yii::t('common', 'Select Fee'),
                    ['order/fee-list'],
                    [
                        'class' => 'btn btn-primary',
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                        'data-header' => Yii::t('common', 'Fee')
                    ]) ?>
            </div>
        </div>
    </div>

    <div class="card-body load-item-container">
        <?php Pjax::begin([
            'linkSelector' => '.pjax__order__level',
            'formSelector' => '#selectionFeeForm',
            'enablePushState' => false,
            'enableReplaceState' => false,
            'timeout' => 30000,
            'id' => 'orderFormSelectFeePjaxContainer',
        ]) ?>
        <?= $this->render('_load_fee', ['model' => $model]) ?>
        <?php Pjax::end() ?>
    </div>
</div> -->