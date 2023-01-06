<?php

namespace common\behaviors\status;

use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * Class StatusBehavior
 *
 * @package common\util
 */
class StatusBehavior extends AttributeBehavior
{
    public $status_attribute = 'status';
    public $value;
    public $default_value = 10;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->status_attribute],
            ];
        }
    }

    /**
     * @param Event $event
     *
     * @return int|mixed
     */
    protected function getValue($event)
    {
        if ($this->value === NULL) {
            if (!isset($this->owner->status)) {
                return $this->default_value;
            }

            return $this->owner->status;
        }

        return parent::getValue($event);
    }
}