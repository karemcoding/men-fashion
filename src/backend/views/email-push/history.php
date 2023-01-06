<?php

use common\models\mailer\EmailHistory;
use common\util\Status;
use yii\bootstrap4\LinkPager;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Notification History');
?>
<div class="card">
    <div class="card-header">
        <?= $this->render('//widgets/_header', [
            'link_btn' => NULL,
            'overview' => NULL,
        ]) ?>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered'],
            'headerRowOptions' => ['class' => 'text-center'],
            'rowOptions' => ['class' => 'text-center'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'receiver',
                'subject',
                'content:html',
                [  
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function ($model) {
                        /** @var EmailHistory $model */
                        $view = function ($title, $class) {
                            return Html::tag('span',
                                Yii::t('common', Yii::t('common', $title)),
                                ['class' => "badge bg-$class text-white"]);
                        };
                        if ($model->status == Status::STATUS_INACTIVE) {
                            return $view('SUCCESS', 'success');
                        }
                        if ($model->status == Status::STATUS_ACTIVE && empty($model->sent_at)) {
                            return $view('UNSENT', 'info');
                        }
                        return $view('ERROR', 'danger');
                    },
                ],
                'sent_at:datetime',
            ],
            'pager' => [
                'class' => LinkPager::class,
                'firstPageLabel' => 'First',
                'lastPageLabel' => 'Last',
                'maxButtonCount' => 10,
            ],
        ]); ?>
    </div>
</div>