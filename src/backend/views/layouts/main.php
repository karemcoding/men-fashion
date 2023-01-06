<?php

/**
 * @var $this View
 * @var $content string
 */

use backend\widgets\app\AppAsset;
use common\widgets\toastr\ToastrAlert;
use yii\helpers\Html;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-dark" id="sidebar">
    <div class="container-fluid">
        <?= $this->render('main/_brand') ?>
        <?= $this->render('main/_user_mobile') ?>
        <?= $this->render('main/_collapse') ?>
    </div>
</nav>
<div class="main-content">
    <?= $this->render('main/_user_desktop') ?>
    <div class="container-fluid pt-4 pb-4">
        <?= $content ?>
    </div>
    <?= ToastrAlert::widget() ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
