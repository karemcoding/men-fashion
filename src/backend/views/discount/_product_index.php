<?php
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var array $allProductDiscount
 */

use backend\models\Product;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

?>
<?php Pjax::begin([
    'enablePushState' => FALSE,
    'enableReplaceState' => FALSE,
    'timeout' => 30000,
    'id' => 'productListPjaxContainer',
]) ?>
<?php ActiveForm::begin([
    'id' => 'selectionProductFormModal',
    'options' => [
        'data-pjax' => TRUE,
    ]]) ?>
<?= GridView::widget([
    'id' => 'productGrid',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered'],
    'headerRowOptions' => ['class' => 'text-center'],
    'rowOptions' => ['class' => 'text-center'],
    'columns' => [
        [
            'class' => CheckboxColumn::class,
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
    ],
    'pager' => [
        'class' => LinkPager::class,
        'firstPageLabel' => 'First',
        'lastPageLabel' => 'Last',
        'maxButtonCount' => 10,
    ],
]); ?>
<div class="form-group text-right">
    <?= Html::button(Yii::t('common', 'Đóng'),
        ['class' => 'btn btn-secondary mr-1', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton(Yii::t('common', 'Thêm'),
        ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end() ?>
<?php Pjax::end() ?>
