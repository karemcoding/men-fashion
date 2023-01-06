<?php
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var array $allProductDiscount
 */

use backend\models\Product;
use yii\bootstrap4\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

?>
<div class="table-responsive">
    <?= GridView::widget([
        'id' => 'productGridCard',
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
            'name',
            [
                'header' => 'Ảnh đại diện',
                'value' => function ($model) {
                    /** @var Product $model */
                    if (!$model->viewThumb) return NULL;
                    return Html::tag('div',
                        Html::img($model->viewThumb, ['class' => 'avatar-img rounded']),
                        [
                            'class' => 'avatar avatar-4by3',
                        ]);
                },
                'format' => 'raw',
            ],
            'price:currency',
            [
                'header' => 'Giá giảm',
                'value' => function ($model) {
                    /** @var Product $model */
                    return Yii::$app->formatter->asCurrency($model->productDiscounts[0]->discount_price ?? NULL);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Thao tác',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return NULL;
                    },
                    'update' => function ($url, $model) {
                        /** @var Product $model */
                        return Html::a("<i class='fe fe-edit'></i>",
                            ['discount/update-discount-mapping', 'id' => $model->productDiscounts[0]->id ?? NULL], [
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxSmallModal',
                                'data-header' => Yii::t('common', $model->name),
                            ]);
                    },
                    'delete' => function ($url, $model) {
                        /** @var Product $model */
                        return Html::a("<i class='fe fe-trash'></i>",
                            ['discount/delete-product-discount', 'id' => $model->productDiscounts[0]->id ?? NULL],
                            [
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxSmallModal',
                                'data-header' => Yii::t('common', $model->name),
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
            'id' => 'productGridCardPager',
        ],
    ]); ?>
</div>