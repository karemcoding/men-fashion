<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductSupplier */

$this->title = Yii::t('common', 'Create Product Supplier');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(Yii::t('common', "<i class='fe fe-arrow-left mr-1'></i>" . 'Quay lại'),
                ['index'], ['class' => 'btn btn-secondary']),
            'overview' => null,
        ]) ?>
    </div>
    <div class="card-body">
        <?= $this->render('_form', ['model' => $model,]) ?>
    </div>
</div>