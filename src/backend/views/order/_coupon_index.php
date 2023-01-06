<?php
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use backend\models\DynamicOrder;
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
    'id' => 'selectionCouponForm',
    'action' => Url::to(['order/create']),
]) ?>
<?php Pjax::begin([
    'enablePushState' => FALSE,
    'enableReplaceState' => FALSE,
    'timeout' => 30000,
    'id' => 'couponListPjaxContainer',
]) ?>
<?= GridView::widget([
    'id' => 'couponGrid',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered'],
    'headerRowOptions' => ['class' => 'text-center'],
    'rowOptions' => ['class' => 'text-center'],
    'columns' => [
        [
            'class' => CheckboxColumn::class,
            'header' => Yii::t('common', 'Chọn'),
            'name' => 'selectionCoupon',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                $checked = FALSE;
                $session = Yii::$app->session->get(DynamicOrder::COUPON_LIST);
                if ($session) {
                    $checked = in_array($model->id, $session);
                }
                return ['checked' => $checked];
            },
        ],
        'name',
        'description',
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
    <?= Html::submitButton(Yii::t('common', 'Lưu'),
        [
            'class' => 'btn btn-primary',
            'name' => 'submitBtn',
            'value' => 'couponList',
        ]) ?>
</div>
<?php ActiveForm::end() ?>

