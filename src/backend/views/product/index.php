<?php

use common\models\Product;
use common\models\ProductCategory;
use common\widgets\columns\ToggleColumn;
use common\widgets\select2\Select2;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('common', 'Sản phẩm');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a("<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                ['product/create'],
                [
                    'class' => 'btn btn-primary',
                ]),
            'overview' => NULL,
        ]) ?>
    </div>
    <div class="card-body">
        <form>
            <div class="row mb-3">
                <div class="input-group col-md-3 mb-3">
                    <?= Html::textInput('name', $filter['name'] ?? NULL,
                        ['class' => 'form-control form-control-prepended height-input',
                            'placeholder' => Yii::t('common', 'Tìm kiếm')]) ?>
                </div>
                <div class="input-group col-md-3 mb-3">
                    <?= Select2::widget([
                        'name' => 'category',
                        'clientOptions' => [
                            'data' => ProductCategory::list2Select($filter['category'] ?? NULL, 'Chọn danh mục'),
                            'escapeMarkup' => new JsExpression("function(m){return m;}"),
                            'templateResult' => new JsExpression("function(data){return data.html;}"),
                        ],
                    ]) ?>
                </div>
                <div class="col-md-3 mb-3 d-flex">
                    <div class="input-group input-group-merge pr-1">
                        <?= Html::textInput('min', $filter['min'] ?? NULL,
                            [
                                'class' => 'form-control form-control-prepended height-input',
                                'placeholder' => Yii::t('common', 'Tồn kho từ'),
                                'type' => 'number',
                            ]) ?>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fe fe-chevron-right"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-merge pl-1">
                        <?= Html::textInput('max', $filter['max'] ?? NULL,
                            [
                                'class' => 'form-control form-control-prepended height-input',
                                'placeholder' => Yii::t('common', 'Tồn kho đến'),
                                'type' => 'number',
                            ]) ?>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fe fe-chevron-left"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="<?= Url::to(['index']) ?>" class="btn btn-white btn-main mr-1">
                        <span class="fe fe-refresh-cw"></span>
                    </a>

                    <button class="btn btn-white btn-main button-search" href="#" type="submit">
                        <i class="fe fe-search"></i>
                    </button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-bordered'],
                'headerRowOptions' => ['class' => 'text-center'],
                'rowOptions' => ['class' => 'text-center'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'category_id',
                        'value' => function ($model) {
                            /** @var Product $model */
                            return $model->category->name ?? NULL;
                        },
                    ],
                    'sku',
                    [
                        'attribute' => 'name',
                        'value' => function (Product $model) {
                            return Html::a($model->name, ['product/update', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'price:currency',
                    [
                        'attribute' => 'discount_price',
                        'format' => 'raw',
                        'value' => function ($model) {
                            /** @var Product $model */
                            if ($model->discountObj) {
                                return Html::a(Yii::$app->formatter->asCurrency($model->orderPrice()),
                                    [
                                        'discount/update', 'id' => $model->discountObj->discount->id,
                                    ]);
                            }
                            return Yii::$app->formatter->asCurrency($model->orderPrice());
                        },
                    ],
                    [
                        'header' => 'Ảnh đại diện',
                        'value' => function ($model) {
                            /** @var \backend\models\Product $model */
                            if (!$model->viewThumb) return NULL;
                            return Html::tag('div',
                                Html::img($model->viewThumb, ['class' => 'avatar-img rounded']),
                                [
                                    'class' => 'avatar avatar-4by3',
                                ]);
                        },
                        'format' => 'raw',
                    ],
                    [   'header' => 'Trạng thái',
                        'class' => ToggleColumn::class,
                        'url' => Url::to(['product/change-status']),
                    ],
                    [
                        'attribute' => 'inventory',
                        'value' => function (Product $model) {
                            if ($model->inventory > 0) {
                                return $model->inventory;
                            }
                            return Html::tag('span', Yii::t('common', 'HẾT HÀNG'), [
                                'class' => 'badge bg-danger text-white',
                            ]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Thao tác',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a("<i class='fe fe-edit'></i>", $url);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a("<i class='fe fe-trash'></i>", $url,
                                    [
                                        'data-confirm' => 'Do you want to delete this item?',
                                        'data-method' => 'post',
                                    ]);
                            },
                        ],
                    ],
                ],
                'pager' => [
                    'class' => LinkPager::class,
                    'firstPageLabel' => 'First',
                    'lastPageLabel' => 'Last',
                    'maxButtonCount' => 10,
                ],
            ]); ?>
        </div>
    </div>
</div>
