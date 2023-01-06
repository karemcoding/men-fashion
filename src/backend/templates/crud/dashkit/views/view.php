<?php

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
echo "<?php\n";
?>
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
?>
<div class="card">
    <div class="card-header">
        <div class="row align-items-end">
            <div class="col">
                <h6 class="header-pretitle badge badge-soft-secondary">
                    Details
                </h6>
                <h1 class="header-title">
                    <?= "<?= " ?>Html::encode($this->title) ?>
                </h1>
            </div>
            <div class="col-auto">
                <?= "<?= " ?>Html::a(<?= $generator->generateString('Update') ?>, ['update', <?= $urlParams ?>],
                ['class' =>
                'btn btn-primary']) ?>
                <?= "<?= " ?>Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
                'class' => 'btn btn-danger',
                'data' => [
                'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                'method' => 'post',
                ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
        <?php
        if (($tableSchema = $generator->getTableSchema()) === false) {
            foreach ($generator->getColumnNames() as $name) {
                echo "            '" . $name . "',\n";
            }
        } else {
            foreach ($generator->getTableSchema()->columns as $column) {
                $format = $generator->generateColumnFormat($column);
                echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
            }
        }
        ?>
        ],
        ]) ?>
    </div>
</div>