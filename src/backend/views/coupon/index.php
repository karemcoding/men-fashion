<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* @var $types [] */

use yii\helpers\Url;

$this->title = Yii::t('app', 'Coupon Templates');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => NULL,
            'overview' => NULL,
        ]) ?>    </div>
    <div class="card-body">
        <div class="row">
            <?php foreach ($types as $key => $type): ?>
                <div class="col-md-4">
                    <a href="<?= Url::to(["coupon/list", 'type' => $key]) ?>">
                        <div class="card">
                            <div class="card-body">
                                <h3>
                                    <?= $type['name'] ?>
                                </h3>
                                <p class="text-muted">
                                    <?= $type['description'] ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>