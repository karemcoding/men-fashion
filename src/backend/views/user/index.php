<?php

use backend\models\User;
use backend\util\Permissions;
use common\models\Role;
use common\util\Status;
use common\widgets\columns\ToggleColumn;
use common\widgets\select2\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $data_provider
 */
$this->title = Yii::t('common', 'Nhân viên');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Yii::$app->user->can(Permissions::USER_UPSERT) ?
                Html::a("<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                    ['user/create'],
                    [
                        'class' => 'btn btn-primary'
                    ]) : NULL,
            'overview' => null,
        ]) ?>
    </div>
    <div class="card-body">
        <form>
            <div class="row mb-3">
                <div class="input-group col-md-3 mb-3">
                    <?= Html::textInput('username', $filter['username'] ?? NULL,
                        [
                            'class' => 'form-control form-control-prepended height-input',
                            'placeholder' => Yii::t('common', 'Tên đăng nhập'),
                        ]) ?>
                </div>
                <div class="input-group col-md-3 mb-3">
                    <?= Select2::widget([
                        'options' => [
                            'prompt' => Yii::t('common', 'Trạng thái'),
                        ],
                        'value' => $filter['state'] ?? NULL,
                        'name' => 'state',
                        'items' => Status::states(),

                    ]) ?>
                </div>
                <div class="input-group col-md-3 mb-3">
                    <?= Select2::widget([
                        'options' => [
                            'prompt' => Yii::t('common', 'Vai trò'),
                        ],
                        'value' => $filter['user_role'] ?? NULL,
                        'name' => 'user_role',
                        'items' => Role::buildSelect2(),
                    ]) ?>

                </div>
                <div class="col-md-3 mb-3">
                    <a href="<?= Url::to(['index']) ?>" class="btn btn-white btn-main mr-1">
                        <span class="fe fe-refresh-cw"></span>
                    </a>

                    <button class="btn btn-white btn-main button-search" href="#" type="submit">
                        <i class="fe fe-search"></i>
                    </button>

                </div>
            </div>
        </form>
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $data_provider,
                'tableOptions' => ['class' => 'table table-bordered'],
                'headerRowOptions' => ['class' => 'text-center'],
                'rowOptions' => ['class' => 'text-center'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a($model->name, ['user/update', 'id' => $model->id]);
                        }
                    ],
                    'username',
                    'email',
                    [
                        'attribute' => 'role_id',
                        'value' => function ($model) {
                            /** @var User $model */
                            return $model->role->name ?? NULL;
                        }
                    ],
                    ['header' => 'Trạng thái','class' => ToggleColumn::class, 'url' => Url::to(['user/change-status'])],
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Thao tác',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return null;
                            },
                            'update' => Yii::$app->user->can(Permissions::USER_UPSERT) ? function ($url, $model) {
                                return Html::a("<i class='fe fe-edit'></i>", $url);
                            } : NULL,
                            'delete' => Yii::$app->user->can(Permissions::USER_DELETE) ? function ($url, $model) {
                                return Html::a("<i class='fe fe-trash'></i>", $url,
                                    [
                                        'data-confirm' => Yii::t('common', 'Do you want to delete this item?'),
                                        'data-method' => 'post',
                                    ]);
                            } : NULL,
                        ],
                    ],
                ]
            ]) ?>
        </div>
    </div>
</div>
