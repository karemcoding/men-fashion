<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 2:45 PM 4/30/2021
 * @projectName baseProject by ANDY
 */

namespace common\widgets\columns;


use common\util\Status;
use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Class StatusColumn
 * @package common\widgets\columns
 */
class StatusColumn extends DataColumn
{
    public $label = 'Status';
    public $attribute = 'status';

    /**
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     * @return string
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($model->status == Status::STATUS_ACTIVE) {
            return Html::tag('span', Yii::t('common', 'Kích hoạt'),
                ['class' => 'badge badge-soft-success']);
        }
        return Html::tag('span', Yii::t('common', 'Chưa kích hoạt'),
            ['class' => 'badge badge-soft-danger']);
    }
}