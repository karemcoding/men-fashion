<?php

use common\models\ProductCategory;
use common\widgets\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Discount */
/* @var $productProvider ActiveDataProvider */

$this->title = $model->name;
?>
    <div class="card">
        <div class="card-header">
            <?= $this->render('//widgets/_header', [
                'link_btn' => Html::a(Yii::t('common', "<i class='fe fe-arrow-left mr-1'></i>" . 'Quay lại danh sách'),
                    ['index'], ['class' => 'btn btn-secondary']),
                'overview' => NULL,
            ]) ?>
        </div>
        <div class="card-body">
            <?= $this->render('_form', ['model' => $model,]) ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-end">
                <div class="col">
                    <h3 class="header-title"><?= Yii::t('common', 'Sản phẩm') ?></h3>
                </div>
                <div class="col-auto">
                    <?= Html::a(
                        Yii::t('common', 'Chọn sản phẩm'),
                        ['discount/product-index', 'discount' => $model->id],
                        [
                            'class' => 'btn btn-primary',
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModal',
                            'data-header' => Yii::t('common', 'Sản phẩm'),
                        ]) ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php Pjax::begin([
                'enablePushState' => FALSE,
                'enableReplaceState' => FALSE,
                'timeout' => 30000,
                'id' => 'productListCardPjax',
            ]) ?>
            <form data-pjax>
                <div class="row mb-3">
                    <div class="input-group col-md-3 mb-3">
                        <?= Html::textInput('name', $filter['name'] ?? NULL,
                            ['class' => 'form-control form-control-prepended height-input',
                                'placeholder' => Yii::t('common', 'Tìm kiếm')]) ?>
                    </div>
                    <div class="input-group col-md-3 mb-3">
                        <?= Select2::widget([
                            'name' => 'category',
                            'clientOptions' => [
                                'data' => ProductCategory::list2Select($filter['category'] ?? NULL, 'Chọn danh mục'),
                                'escapeMarkup' => new JsExpression("function(m){return m;}"),
                                'templateResult' => new JsExpression("function(data){return data.html;}"),
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-3 mb-3 d-flex">
                        <div class="input-group input-group-merge pr-1">
                            <?= Html::textInput('min', $filter['min'] ?? NULL,
                                [
                                    'class' => 'form-control form-control-prepended height-input',
                                    'placeholder' => Yii::t('common', 'Tồn kho từ'),
                                    'type' => 'number',
                                ]) ?>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <span class="fe fe-chevron-right"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group input-group-merge pl-1">
                            <?= Html::textInput('max', $filter['max'] ?? NULL,
                                [
                                    'class' => 'form-control form-control-prepended height-input',
                                    'placeholder' => Yii::t('common', 'Tồn kho đến'),
                                    'type' => 'number',
                                ]) ?>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <span class="fe fe-chevron-left"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= Url::to(['discount/update', 'id' => $model->id]) ?>"
                           class="btn btn-white btn-main mr-1">
                            <span class="fe fe-refresh-cw"></span>
                        </a>

                        <button class="btn btn-white btn-main button-search" href="#" type="submit">
                            <i class="fe fe-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <?php Pjax::begin([
                'enablePushState' => FALSE,
                'enableReplaceState' => FALSE,
                'timeout' => 30000,
                'id' => 'productListCardBodyPjax',
            ]) ?>
            <?= $this->render('_product_list', ['dataProvider' => $productProvider]) ?>
            <?php Pjax::end() ?>
            <?php Pjax::end() ?>
        </div>
    </div>
    <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ajaxModalTitle"></h4>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <div class="modal fade ajax__modal" id="ajaxSmallModal" tabindex="-1" role="dialog" aria-labelledby="ajaxModalTitle"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ajaxModalTitle"></h4>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
<?php
$url = Url::to(['discount/product-discount', 'discount' => $model->id]);
$js = <<<JS
    $(document).on('pjax:complete','#productListPjaxContainer', function(event) {
        $.pjax.reload('#productListCardBodyPjax',{
            push: false,
            replace: false,
            timeout:30000,
            url:"{$url}",
            scrollTo:false
        });
    });
    $(document).on('submit', '#productDiscountMappingForm,#delete__form', function(event) {
        $.pjax.submit(event, '#productListCardBodyPjax',{
            push: false,
            replace: false,
            timeout:30000,
            scrollTo:false
        });
    });
    $(document).on('pjax:complete','#productListCardPjax', function(event) {
        $('#ajaxSmallModal').modal('hide');
    });
JS;
$this->registerJs($js);