<?php
/**
 * @var View $this
 * @var InventoryHistory $model
 * @var array $selections
 */

use common\models\InventoryHistory;
use common\widgets\select2\Select2;
use yii\helpers\Html;
use yii\web\View;

?>
<div class="w-100">
    <?= Select2::widget([
        'model' => $model,
        'attribute' => 'reason_id',
        'items' => $selections
    ]) ?>
    <?= Html::error($model, 'reason_id', ['class' => 'invalid-feedback']) ?>
</div>
