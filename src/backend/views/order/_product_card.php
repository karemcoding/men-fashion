<?php
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
                    <?= Yii::t('common', 'Items') ?>
                </h3>
            </div>
            <div class="col-auto">
                <?= Html::a(
                    Yii::t('common', 'Select/Remove Item'),
                    ['order/product-index'],
                    [
                        'class' => 'btn btn-primary',
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                        'data-header' => Yii::t('common', 'Sản phẩm')
                    ]) ?>
            </div>
        </div>
    </div>

    <div class="card-body load-item-container">
        <?php Pjax::begin([
            'linkSelector' => '.pjax__order__level',
            'formSelector' => '#selectionProductForm',
            'enablePushState' => false,
            'enableReplaceState' => false,
            'timeout' => 30000,
            'id' => 'orderFormSelectProductPjaxContainer',
        ]) ?>
        <?= $this->render('_load_item', ['model' => $model]) ?>
        <?php Pjax::end() ?>
    </div>
</div>