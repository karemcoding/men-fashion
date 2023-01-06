<?php


namespace common\models;


use common\behaviors\audit\AuditBehavior;
use common\util\Status;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * Class ActiveRecord
 * @package common\models
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    public static $alias = 'main';

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::class,
            'blameable' => BlameableBehavior::class,
            'audit' => [
                'class' => AuditBehavior::class,
                'softDeleteWhen' => 'softDeleteWhen',
            ],
        ];
    }

    /**
     * @param ActiveRecord $model
     * @return bool
     */
    public function softDeleteWhen(self $model)
    {
        return $model->hasAttribute('status') && $model->status == Status::STATUS_DELETED;
    }

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }

    /**
     * @return bool
     */
    public function softDelete()
    {
        if ($this->hasAttribute('status')) {
            $this->status = Status::STATUS_DELETED;
            return $this->save();
        }
        return FALSE;
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateDuplicate($attribute, $params, $validator)
    {
        $model = static::find()
            ->andWhere([$attribute => $this->$attribute])
            ->notDeleted();
        if ($this->id != NULL) {
            $model->andWhere(['<>', static::$alias . '.id', $this->id]);
        }
        if ($model->exists()) {
            $this->addError($attribute, "\"{$this->$attribute}\" has already been taken.");
        }
    }
}