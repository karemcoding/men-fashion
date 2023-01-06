<?php
/**
 * @var View $this
 * @var DynamicOrder $model
 * @var ActiveForm $form
 */

use backend\models\DynamicOrder;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

?>
<?php if (!empty($model->products)): ?>
    <div class="table-responsive">
        <div class="grid-view">
            <table class="table table-bordered">
                <thead>
                <tr class="text-center">
                    <th>SKU</th>
                    <th>Ảnh đại diện</th>
                    <th>Tên</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model->products as $item): ?>
                    <tr class="text-center">
                        <td width="20%">
                            <?= Html::decode($item->sku) ?>
                        </td>
                        <td>
                            <div class="avatar avatar-4by3">
                                <img class="avatar-img rounded"
                                     src="<?= $item->viewThumb ?>" alt="...">
                            </div>
                        </td>
                        <td width="30%">
                            <?= Html::decode($item->name) ?>
                        </td>
                        <td width="20%">
                            <?= $item->orderPrice() ?>
                        </td>
                        <td width="10%">
                            <?php
                            $cssClass = NULL;
                            if (!empty($model->firstErrors["product[$item->id]"])) {
                                $cssClass = 'is-invalid';
                            } elseif (!empty($model->firstSuccesses["product[$item->id]"])) {
                                $cssClass = 'is-valid';
                            }
                            echo Html::textInput("productQuantity[$item->id]",
                                Yii::$app->session->get(DynamicOrder::PRODUCT_QUANTITY)[$item->id] ?? 1,
                                [
                                    'class' => "quantity__ipt form-control text-center $cssClass",
                                    'type' => 'number',
                                    'data-id' => $item->id,
                                ]);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    Not Found
<?php endif; ?>