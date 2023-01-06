<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

    <?php foreach ($generator->getColumnNames() as $attribute) {
        if (in_array($attribute, $safeAttributes)) {
            echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
        }
    } ?>
    <div class="form-group text-right">
        <?= "<?=" ?> Html::a(Yii::t('common', 'Hủy'),
        ['<?= $generator->controllerID ?>/index'],
        ['class' => 'btn btn-secondary mr-1']) ?>
        <?= "<?=" ?> Html::submitButton(Yii::t('common', 'Lưu'),
        ['class' => 'btn btn-primary']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
