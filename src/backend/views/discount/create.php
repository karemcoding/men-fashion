<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Discount */

$this->title = Yii::t('common', 'Tạo chương trình giảm giá');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(Yii::t('common', "<i class='fe fe-arrow-left mr-1'></i>" . 'Quay lại danh sách'),
                ['index'], ['class' => 'btn btn-secondary']),
            'overview' => NULL,
        ]) ?>
    </div>
    <div class="card-body">
        <?= $this->render('_form', ['model' => $model,]) ?>
    </div>
</div>