<?php

namespace common\util;

use common\models\settings\Setting as Model;
use yii\base\Component;

/**
 * Class Setting
 *
 * @package common\util
 */
class Setting extends Component
{

    /**
     * @var Model
     */
    private $_value;

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->_value = new Model();
        $this->_value->getValues();
        parent::init();
    }

    public function get()
    {
        return $this->_value;
    }
}
