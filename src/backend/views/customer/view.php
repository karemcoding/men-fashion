<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = $model->id;
?>
<div class="card">
    <div class="card-header">
        <div class="row align-items-end">
            <div class="col">
                <h1 class="header-title">
                    <?= Html::encode($this->title) ?>
                </h1>
            </div>
            <div class="col-auto">
                <?= Html::a(Yii::t('common', 'Cập nhật'), ['update', 'id' => $model->id], ['class' =>
                    'btn btn-primary']) ?>
                <?= Html::a(Yii::t('common', 'Xóa'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('common', 'Bạn có muốn xóa?'),
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
                'username',
                'email:email',
                'phone',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'verification_token',
                'status',
                'created_at',
                'updated_at',
            ],
        ]) ?>
    </div>
</div>