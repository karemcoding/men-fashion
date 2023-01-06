<?php
/**
 * @var View $this
 * @var DynamicOrder $model
 */

use backend\models\DynamicOrder;
use yii\helpers\Html;
use yii\web\View;

?>
<?php if (!empty($model->coupons)): ?>
    <div class="table-responsive">
        <div class="grid-view">
            <table class="table table-bordered">
                <thead>
                <tr class="text-center">
                    <th><?= Yii::t('common', 'Name') ?></th>
                    <th><?= Yii::t('common', 'Description') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model->coupons as $item): ?>
                    <tr class="text-center">
                        <td width="50%">
                            <?= Html::decode($item->name) ?>
                        </td>
                        <td width="30%">
                            <?= Html::decode($item->description) ?>
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
