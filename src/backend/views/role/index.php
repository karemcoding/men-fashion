<?php

use backend\util\Permissions;
use common\models\Role;
use common\util\Status;
use common\widgets\select2\Select2;
use common\widgets\toggle\ToggleInput;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('common', 'Nhóm nhân viên');
?>
    <div class="card">
        <div class="card-header">
            <?= $this->render('//widgets/_header', [
                'link_btn' => Yii::$app->user->can(Permissions::ROLE_UPSERT) ?
                    Html::a("<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
                        ['create'],
                        [
                            'class' => 'btn btn-primary',
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModal',
                            'data-header' => Yii::t('common', 'Add Staff Group')
                        ]) : NULL,
                'overview' => null,
            ]) ?>
        </div>
        <div class="card-body">
            <form>
                <div class="row mb-3">
                    <div class="input-group col-md-3 mb-3">
                        <?= Html::textInput('name', $filter['name'] ?? NULL,
                            [
                                'class' => 'form-control form-control-prepended height-input',
                                'placeholder' => Yii::t('common', 'Tìm kiếm')
                            ]) ?>
                    </div>
                    <div class="input-group col-md-3 mb-3">
                        <?= Select2::widget([
                            'options' => [
                                'prompt' => 'Trạng thái',
                            ],
                            'value' => $filter['state'] ?? NULL,
                            'name' => 'state',
                            'items' => Status::states(),
                        ]) ?>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= Url::to(['index']) ?>" class="btn btn-white btn-main mr-1">
                            <span class="fe fe-refresh-cw"></span>
                        </a>
                        <button class="btn btn-main btn-white" type="submit">
                            <i class="fe fe-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => "table table-bordered"],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'name',
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->isAdmin()) {
                                    return ToggleInput::widget(
                                        [
                                            'checked' => TRUE,
                                            'disabled' => true,
                                        ]);
                                }
                                return ToggleInput::widget(
                                    [
                                        'checked' => $model->status,
                                        'action' =>
                                            [
                                                'url' => Url::to(['role/change-status']),
                                                'request_type' => 'POST',
                                                'sender' => $model->id,
                                            ]
                                    ]);
                            }
                        ],
                        [
                            'attribute' => 'created_by',
                            'value' => function (Role $model) {
                                return $model->author->username;
                            }
                        ],
                        [
                            'attribute' => 'updated_by',
                            'value' => function (Role $model) {
                                return $model->updater->username;
                            }
                        ],
                        'created_at:datetime',
                        'updated_at:datetime',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Thao tác',
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center'],
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return null;
                                },
                                'update' => function ($url, $model, $key) {
                                    if (Yii::$app->user->can(Permissions::ROLE_UPSERT)) {
                                        return Html::a("<i class='fe fe-edit'></i>", $url,
                                            [
                                                'data-toggle' => 'modal',
                                                'data-target' => '#ajaxModal',
                                                'data-header' => $model->name
                                            ]);
                                    }
                                    return null;
                                },
                                'delete' => function ($url, $model, $key) {
                                    /** @var Role $model */
                                    if ($model->isAdmin()) {
                                        return null;
                                    }
                                    if (!Yii::$app->user->can(Permissions::ROLE_DELETE)) {
                                        return null;
                                    }
                                    return Html::a("<i class='fe fe-trash'></i>", $url,
                                        [
                                            'data-confirm' => Yii::t('common', 'Do you want to delete this item?'),
                                            'data-method' => 'post',
                                        ]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
<?= $this->render('//widgets/_modal') ?>