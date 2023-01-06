<?php
/**
 * @var View $this
 * @var ExpressCompany $model
 */

use common\models\ExpressCompany;
use yii\helpers\Html;
use yii\web\View;

?>
<table class="d-inline-block table-borderless contact__table">
    <tbody>
    <tr>
        <td><span class="fe fe-phone"></span></td>
        <td>:</td>
        <td class="text-left"><?= Html::encode($model->tel) ?></td>
    </tr>
    <tr>
        <td><span class="fe fe-map-pin"></span></td>
        <td>:</td>
        <td class="text-left"><?= Html::encode($model->address) ?></td>
    </tr>
    <tr>
        <td><span class="fe fe-link"></span></td>
        <td>:</td>
        <td class="text-left">
            <?= Html::a($model->website,
                $model->website,
                [
                    'target' => '_blank',
                ]) ?>
        </td>
    </tr>
    </tbody>
</table>