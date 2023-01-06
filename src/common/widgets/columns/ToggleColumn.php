<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 2:45 PM 4/30/2021
 * @projectName baseProject by ANDY
 */

namespace common\widgets\columns;


use common\util\Status;
use common\widgets\toggle\ToggleInput;
use Exception;
use yii\grid\DataColumn;

/**
 * Class ToggleColumn
 * @package common\widgets\columns
 */
class ToggleColumn extends DataColumn
{
    public $label = 'Status';
    public $attribute = 'status';
    public $inputClass = ToggleInput::class;
    public $url;

    /**
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     * @return string
     * @throws Exception
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return ToggleInput::widget([
            'active_value' => Status::STATUS_ACTIVE,
            'inactive_value' => Status::STATUS_INACTIVE,
            'checked' => $model->status == Status::STATUS_ACTIVE,
            'action' => [
                'url' => $this->url,
                'request_type' => 'POST',
                'sender' => $model->id,
            ],
        ]);
    }
}