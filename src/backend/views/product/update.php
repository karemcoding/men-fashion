<?php

/**
 * @var $this             yii\web\View
 * @var $model            common\models\Product
 * @var $inventoryLogData yii\data\ActiveDataProvider
 */

use common\models\InventoryHistory;
use common\widgets\select2\Select2Asset;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = $model->name;
Select2Asset::register($this);
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(
                    Yii::t('common', "<i class='fe fe-clipboard mr-1'></i>" . 'Duplicate'),
                    ['product/duplicate', 'id' => $model->id],
                    ['class' => 'btn btn-primary']
                ) . Html::a(
                    Yii::t('common', "<i class='fe fe-arrow-left mr-1'></i>" . 'Quay lại'),
                    ['index'],
                    ['class' => 'btn btn-secondary ml-2']
                ),
            'overview' => null,
        ]) ?>
    </div>
    <div class="card-body">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="row align-items-end">
            <div class="col">
                <h2 class="header-title">
                    <?= Yii::t('common', 'Inventory Log') ?>
                </h2>
            </div>
            <div class="col-auto">
                <?= Html::a(
                    "<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                    ['product/add-inventory', 'id' => $model->id],
                    ['class' => 'btn btn-primary']
                ) ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-lg-6 col-xl">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center gx-0">
                            <div class="col">
                                <h6 class="text-uppercase text-muted mb-2">
                                    <?= Yii::t('common', 'Tồn kho') ?>
                                </h6>
                                <span class="h2 mb-0">
                                    <?= $model->inventory ?>
                                </span>
                            </div>
                            <div class="col-auto">
                                <span class="h2 fe fe-hash text-muted mb-0"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center gx-0">
                            <div class="col">
                                <h6 class="text-uppercase text-muted mb-2">
                                    <?= Yii::t('common', 'Đã bán') ?>
                                </h6>
                                <span class="h2 mb-0">
                                    <?= $model->sold ?>
                                </span>
                            </div>
                            <div class="col-auto">
                                <span class="h2 fe fe-hash text-muted mb-0"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <?php
            Pjax::begin([
                            'enablePushState'    => false,
                            'enableReplaceState' => false,
                            'timeout'            => 30000,
                            'id'                 => 'inventoryLogPjaxContainer',
                        ]) ?>
            <?= GridView::widget([
                                     'dataProvider'     => $inventoryLogData,
                                     'tableOptions'     => ['class' => 'table table-bordered'],
                                     'headerRowOptions' => ['class' => 'text-center'],
                                     'rowOptions'       => ['class' => 'text-center'],
                                     'columns'          => [
                                         ['class' => 'yii\grid\SerialColumn'],
                                         'quantity',
                                         [
                                             'attribute' => 'type',
                                             'value'     => function (InventoryHistory $inventoryLog) {
                                                 return InventoryHistory::generateTypeView(
                                                 )[$inventoryLog->type][0] ?? null;
                                             },
                                             'format'    => 'html',
                                         ],
                                         'ref',
                                         [
                                             'attribute' => 'reason_id',
                                             'value'     => function (InventoryHistory $inventoryLog) {
                                                 return $inventoryLog->reason->reason ?? null;
                                             },
                                         ],
                                         [
                                             'attribute' => 'warehouse_id',
                                             'value'     => function (InventoryHistory $inventoryLog) {
                                                 return $inventoryLog->warehouse->name ?? null;
                                             },
                                         ],
                                         'created_at:datetime',
                                     ],
                                     'pager'            => [
                                         'class'          => LinkPager::class,
                                         'firstPageLabel' => 'First',
                                         'lastPageLabel'  => 'Last',
                                         'maxButtonCount' => 10,
                                     ],
                                 ]); ?>
            <?php
            Pjax::end() ?>
        </div>
    </div>
</div>
<?= $this->render('//widgets/_modal') ?>
