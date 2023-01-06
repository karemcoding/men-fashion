<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$modelClassName = Inflector::camel2words(StringHelper::basename($generator->modelClass));
$nameAttributeTemplate = '$model->' . $generator->getNameAttribute();
$titleTemplate = $generator->generateString('Update ' . $modelClassName . ': {name}', ['name' => '{nameAttribute}']);
if ($generator->enableI18N) {
    $title = strtr($titleTemplate, ['\'{nameAttribute}\'' => $nameAttributeTemplate]);
} else {
    $title = strtr($titleTemplate, ['{nameAttribute}\'' => '\' . ' . $nameAttributeTemplate]);
}

echo "<?php\n";
?>

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $nameAttributeTemplate ?>;
?>
<div class="card">
    <div class="card-header">
        <?= "<?=" ?> $this->render('//widgets/_header', [
        'link_btn' => Html::a(Yii::t('common', "<i class='fe fe-arrow-left mr-1'></i>" . 'Quay lại danh sách'),
        ['index'], ['class' => 'btn btn-secondary']),
        'overview' => null,
        ]) ?>
    </div>
    <div class="card-body">
        <?= "<?= " ?>$this->render('_form', [ 'model' => $model, ]) ?>
    </div>
</div>
