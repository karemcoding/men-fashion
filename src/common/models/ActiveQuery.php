<?php

namespace common\models;

use common\util\Status;

/**
 * Class ActiveQuery
 * @package common\models
 */
class ActiveQuery extends \yii\db\ActiveQuery
{
    protected $_alias;

    public function init()
    {
        parent::init();
        /**@var ActiveRecord $model */
        $model = $this->modelClass;

        $this->_alias = $model::$alias ?? 'main';
        $this->alias($this->_alias);
    }

    /**
     * @return ActiveQuery
     */
    public function notDeleted(): ActiveQuery
    {
        return $this->andWhere([
                "<>",
                $this->_alias . '.status',
                Status::STATUS_DELETED
            ]
        );
    }

    /**
     * @return ActiveQuery
     */
    public function active(): ActiveQuery
    {
        return $this->andWhere([$this->_alias . '.status' => Status::STATUS_ACTIVE]);
    }
}