<?php
/**
 * @var View $this
 */

use yii\web\View;

$this->title = Yii::t('common', 'Order Not Completed');
?>
<div class="container my-auto">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="payment">
                <div class="payment_header">
                    <div class="check"><i class="fa fa-close i--error" aria-hidden="true"></i></div>
                </div>
                <div class="content">
                    <h1><?= Yii::t('common', 'Order Not Completed') ?></h1>
                    <p><?= Yii::t('common', 'You can check the status of the order at any time in the Purchase History') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
