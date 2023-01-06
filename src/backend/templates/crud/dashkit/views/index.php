<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\bootstrap4\LinkPager;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
?>
<div class="card">
    <div class="card-header">
        <?= "<?=" ?>$this->render('//widgets/_header', [
        'link_btn' => Html::a("<i class='fe fe-plus mr-1'></i>" . Yii::t('common', 'Thêm'),
        ['<?= $generator->controllerID ?>/create'],['class' => 'btn btn-primary']),
        'overview' => null,
        ]) <?= "?>" ?>
    </div>
    <div class="card-body">
        <?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : '' ?>
        <?php if (!empty($generator->searchModelClass)): ?>
            <?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
        <?php endif; ?>
        <?php if ($generator->indexWidgetType === 'grid'): ?>
            <?= "<?= " ?>GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered'],
            'headerRowOptions' => ['class' => 'text-center'],
            'rowOptions' => ['class' => 'text-center'],
            <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            ['class' => 'yii\grid\SerialColumn'],
            <?php
            $count = 0;
            if (($tableSchema = $generator->getTableSchema()) === false) {
                foreach ($generator->getColumnNames() as $name) {
                    if (++$count < 6) {
                        echo "            '" . $name . "',\n";
                    } else {
                        echo "            //'" . $name . "',\n";
                    }
                }
            } else {
                foreach ($tableSchema->columns as $column) {
                    $format = $generator->generateColumnFormat($column);
                    if (++$count < 6) {
                        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                    } else {
                        echo "            //'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                    }
                }
            }
            ?>
            [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Thao tác',
            'buttons' => [
            'update' => function ($url, $model) {
            return Html::a("<i class='fe fe-edit'></i>", $url);
                                                      },
                                                      'delete' => function ($url, $model) {
                                                      return Html::a("<i class='fe fe-trash'></i>", $url,
            [
            'data-confirm' => 'Do you want to delete this item?',
            'data-method' => 'post',
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
        <?php else: ?>
            <?= "<?= " ?>ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
            },
            ]) ?>
        <?php endif; ?>
        <?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : '' ?>
    </div>
</div>