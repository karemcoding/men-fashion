<?php
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider ;
 */

use backend\models\DynamicOrder;
use backend\models\Product;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

?>
<?php ActiveForm::begin([
    'id' => 'selectionProductForm',
    'action' => Url::to(['order/create']),
]) ?>
<?php Pjax::begin([
    'enablePushState' => FALSE,
    'enableReplaceState' => FALSE,
    'timeout' => 30000,
    'id' => 'productListPjaxContainer',
]) ?>
<?= GridView::widget([
    'id' => 'productGrid',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered'],
    'headerRowOptions' => ['class' => 'text-center'],
    'rowOptions' => ['class' => 'text-center'],
    'columns' => [
        [
            'class' => CheckboxColumn::class,
            'header' => Yii::t('common', 'Select'),
            'checkboxOptions' => function ($model, $key, $index, $column) {
                $checked = FALSE;
                $session = Yii::$app->session->get(DynamicOrder::ORDER_PRODUCT_LIST);
                if ($session) {
                    $checked = in_array($model->id, $session);
                }
                return ['checked' => $checked];
            },
        ],
        [
            'attribute' => 'category_id',
            'value' => function ($model) {
                /** @var Product $model */
                return $model->category->name ?? NULL;
            },
        ],
        'name',
        'price:currency',
        [
            'attribute' => 'discount_price',
            'value' => function ($model) {
                return Yii::$app->formatter->asCurrency($model->orderPrice());
            },
        ],
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
    ],
    'pager' => [
        'class' => LinkPager::class,
        'firstPageLabel' => 'First',
        'lastPageLabel' => 'Last',
        'maxButtonCount' => 10,
    ],
]); ?>
<?php Pjax::end() ?>
    <div class="form-group text-right">
        <?= Html::button(Yii::t('common', 'Hủy'),
            ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
        <?= Html::submitButton(Yii::t('common', 'Save'),
            [
                'class' => 'btn btn-primary',
                'name' => 'submitBtn',
                'value' => 'productList',
            ]) ?>
    </div>
<?php ActiveForm::end() ?>