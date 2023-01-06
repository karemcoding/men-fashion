<?php

namespace api\models;

use Yii;
use yii\base\Model;

/**
 * Class Profile
 * @package common\models
 */
class Profile extends Model
{
    public $phone;
    public $name;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['phone'], 'string', 'max' => 30],
            [['phone'], 'validateDuplicate'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateDuplicate($attribute, $params, $validator)
    {
        $model = Customer::find()
            ->andWhere([$attribute => $this->$attribute])
            ->andWhere(['<>', 'id', Yii::$app->user->identity->id])
            ->notDeleted();
        if ($model->exists()) {
            $this->addError($attribute, "\"{$this->$attribute}\" has already been taken.");
        }
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return FALSE;
        }
        /** @var Customer $model */
        $model = Yii::$app->user->identity;
        if ($this->phone) {
            $model->phone = $this->phone;
        }
        if ($this->name) {
            $model->name = $this->name;
        }
        return $model->save(FALSE);
    }
}