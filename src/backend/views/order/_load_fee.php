<?php
/**
 * @var View $this
 * @var DynamicOrder $model
 */

use backend\models\DynamicOrder;
use common\models\Fee;
use yii\helpers\Html;
use yii\web\View;

?>
<?php if (!empty($model->fees)): ?>
    <div class="table-responsive">
        <div class="grid-view">
            <table class="table table-bordered">
                <thead>
                <tr class="text-center">
                    <th><?= Yii::t('common', 'Name') ?></th>
                    <th><?= Yii::t('common', 'Type') ?></th>
                    <th><?= Yii::t('common', 'Value') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model->fees as $item): ?>
                    <tr class="text-center">
                        <td width="50%">
                            <?= Html::decode($item->name) ?>
                        </td>
                        <td width="30%">
                            <?= Fee::selectType()[$item->type] ?>
                        </td>
                        <td width="20%">
                            <?php
                            if ($item->type == Fee::TYPE_PERCENT) {
                                echo $item->value . "%";
                            } else {
                                echo Yii::$app->formatter->asCurrency($item->value);
                            }
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
