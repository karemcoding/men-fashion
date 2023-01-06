<?php

/**
 * @var $this yii\web\View
 * @var $name string
 * @var $message string
 * @var $exception Exception
 */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-5 col-xl-4 my-5">
        <div class="text-center">
            <h6 class="text-uppercase text-muted mb-4">
                error
            </h6>
            <h1 class="display-4 mb-3">
                <?= Html::encode($this->title) ?> 😭
            </h1>
            <p class="text-muted mb-4">
                <?= nl2br(Html::encode($message)) ?>
            </p>
            <a href="<?= Yii::$app->homeUrl ?>" class="btn btn-lg btn-primary">
                Return to your dashboard
            </a>
        </div>
    </div>
</div>