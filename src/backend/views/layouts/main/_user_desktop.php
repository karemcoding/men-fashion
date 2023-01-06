<?php

use backend\widgets\app\AppAsset;
use common\models\Message;
use yii\bootstrap4\Html;

$asset = AppAsset::register($this);
?>
<nav class="navbar navbar-expand-md navbar-light d-none d-md-flex" id="topbar">
    <div class="container-fluid">
        <div class="navbar-user ml-auto">
            <div class="dropdown mr-3 d-md-flex">
                <!-- Notification bell -->
                <?= Html::beginTag('a', [
                    'class' => 'navbar-user-link',
                    'href' => '#',
                    'role' => 'button',
                    'data-toggle' => 'dropdown',
                    'aria-haspopup' => 'true',
                    'aria-expanded' => 'false'
                ]) ?>
                <!-- <span class="icon active">
                  <i class="fe fe-bell"></i>
                </span> -->
                <?= Html::endTag('a') ?>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-card">
                    <!-- <div class="card-header">
                        <h5 class="card-header-title">
                            Notifications
                        </h5>
                        <?= Html::a('View all', ['#'], ['class' => 'small']) ?>
                    </div> -->
                    <div class="card-body">
                        <div class="list-group list-group-flush list-group-activity my-n3">
                            <a class="list-group-item text-reset" href="#!">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-sm">
                                            <?= Html::img(Yii::$app->user->identity->viewAvatar(),
                                                [
                                                    'class' => 'avatar-img rounded-circle',
                                                    'alt' => '...'
                                                ]) ?>
                                        </div>
                                    </div>
                                    <!-- <div class="col ml-n2">
                                        <div class="small">
                                            <strong>Dianna Smiley</strong> shared your post with Ab Hadley, Adolfo
                                            Hess,
                                            and 3 others.
                                        </div>
                                        <small class="text-muted">2m ago</small>
                                    </div> -->
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="dropdown mr-3 d-md-flex">
                <?= Html::a(Message::language2Select()[Yii::$app->language],
                    ['#'],
                    [
                        'type' => 'button',
                        'id' => 'dropdownMenuButtonAliasLanguage',
                        'data-toggle' => 'dropdown',
                        'aria-haspopup' => 'true',
                        'aria-expanded' => 'false',
                        'class' => 'dropdown-item p-0'
                    ]) ?>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonAliasLanguage">
                    <?php foreach (Message::language2Select() as $key => $value): ?>
                        <?= Html::a($value,
                            ['multi-language/set', 'code' => $key],
                            [
                                'class' => 'dropdown-item',
                                'data-method' => 'POST'
                            ]) ?>
                    <?php endforeach; ?>
                </div>
            </div> -->
            <!--Avatar-->
            <div class="dropdown">
                <?= Html::beginTag('a', [
                    'href' => '#',
                    'class' => 'avatar avatar-sm avatar-online dropdown-toggle',
                    'role' => 'button',
                    'data-toggle' => 'dropdown',
                    'aria-haspopup' => 'true',
                    'aria-expanded' => 'false'
                ]) ?>
                <?= Html::img(Yii::$app->user->identity->viewAvatar(),
                    [
                        'class' => 'avatar-img rounded-circle',
                        'alt' => '...'
                    ]) ?>
                <?= Html::endTag('a') ?>
                <div class="dropdown-menu dropdown-menu-right">
                    <?= Html::a('Thông tin cá nhân', ['profile/index'], ['class' => 'dropdown-item']) ?>
                    <hr class="dropdown-divider">
                    <?= Html::a('Đăng xuất', ['site/logout'], [
                        'class' => 'dropdown-item',
                        'data-method' => 'post'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</nav>
