<?php

/**
 * @var $this yii\web\View
 * @var $model common\models\ProductCategory
 */

use yii\helpers\Html;

$this->title = $model->name;
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a("<i class='fe fe-arrow-left mr-1'></i>" . Yii::t('common', 'Quay lại'),
                ['index'], ['class' => 'btn btn-secondary']),
            'overview' => null,
        ]) ?>
    </div>
    <div class="card-body">
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>