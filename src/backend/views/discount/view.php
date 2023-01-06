<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Discount */

$this->title = $model->name;
?>
<div class="card">
    <div class="card-header">
        <div class="row align-items-end">
            <div class="col">
                <h6 class="header-pretitle badge badge-soft-secondary">
                    Details
                </h6>
                <h1 class="header-title">
                    <?= Html::encode($this->title) ?>
                </h1>
            </div>
            <div class="col-auto">
                <?= Html::a(Yii::t('common', 'Update'), ['update', 'id' => $model->id],
                    ['class' =>
                        'btn btn-primary']) ?>
                <?= Html::a(Yii::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('common', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'description',
                'default_value',
                'from',
                'to',
                'type',
                'status',
                'created_by',
                'updated_by',
                'created_at',
                'updated_at',
            ],
        ]) ?>
    </div>
</div>