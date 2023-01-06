<?php

use common\widgets\select2\Select2Asset;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\bootstrap4\LinkPager;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Language');
Select2Asset::register($this);
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => Html::a(Yii::t('common', 'Create'),
                ['multi-language/add-source'], [
                    'class' => 'btn btn-primary',
                    'data-toggle' => 'modal',
                    'data-target' => '#ajaxModal',
                    'data-header' => Yii::t('common', 'Create')
                ]),
            'overview' => null,
        ]) ?>    </div>
    <div class="card-body">
        <?= GridView::widget([
            'moduleId' => 'gridviewKrajee',
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'striped' => false,
            'tableOptions' => ['class' => 'table table-bordered'],
            'headerRowOptions' => ['class' => 'text-center'],
            'rowOptions' => ['class' => 'text-center'],
            'dataColumnClass' => DataColumn::class,
            'columns' => [
                [
                    'class' => DataColumn::class,
                    'label' => 'Content',
                    'group' => true,
                    'groupOddCssClass' => 'kv-group-odd align-middle',
                    'value' => function ($model, $key, $index, $column) {
                        $html = Html::beginTag('div');
                        $html .= $model->source->message;
                        $html .= Html::beginTag('span', ['class' => 'm-2']);
                        $html .= Html::a("<i class='fe fe-plus-square'></i>",
                            ['multi-language/add', 'source' => $model->source->id],
                            [
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModal',
                                'data-header' => Yii::t('common', 'Create')
                            ]);
                        $html .= Html::a("<i class='fe fe-edit'></i>",
                            ['multi-language/edit-source', 'source' => $model->source->id],
                            [
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModal',
                                'data-header' => Yii::t('common', 'Confirm')
                            ]);
                        $html .= Html::a("<i class='fe fe-trash'></i>",
                            ['multi-language/delete-source', 'id' => $model->source->id],
                            [
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModal',
                                'data-header' => Yii::t('common', 'Confirm')
                            ]
                        );
                        $html .= Html::beginTag('span');
                        $html .= Html::endTag('div');
                        return $html;
                    },
                    'format' => 'raw'
                ],
                'language',
                'translation',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Thao tác',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a("<i class='fe fe-edit'></i>", $url, [
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModal',
                                'data-header' => Yii::t('common', 'Update')
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a("<i class='fe fe-trash'></i>", $url,
                                [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                    'data-header' => Yii::t('common', 'Confirm')
                                ]);
                        },
                    ],
                ],
            ],
            'pager' => [
                'class' => LinkPager::class,
                'firstPageLabel' => 'First',
                'lastPageLabel' => 'Last',
                'maxButtonCount' => 10
            ],
        ]); ?>
    </div>
</div>
<div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ajaxModalTitle"></h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>