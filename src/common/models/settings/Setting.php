<?php

namespace common\models\settings;

use ReflectionClass;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class Setting
 *
 * @package common\models
 *
 * @property-read void $values
 */
class Setting extends Model
{

    /**
     * @var array
     */
    private $_setting;

    /**
     * @throws \ReflectionException
     */
    public function getValues()
    {
        if ($this->_setting === NULL) {
            $this->_setting = ArrayHelper::map(SystemSetting::find()->asArray()->all(), 'key',
                'value');
        }

        foreach ($this->attributes as $key => $attribute) {
            $setting_key = $this->_generateKey($this, $key);
            $this->$key = $this->_setting[$setting_key] ?? NULL;
        }
    }

    /**
     * @param $class
     * @param $key
     *
     * @return string
     * @throws \ReflectionException
     */
    private function _generateKey($class, $key)
    {
        $class_name = (new ReflectionClass($class))->getShortName();
        $prefix = strtoupper($class_name) . '-';
        $key_ = $prefix . strtoupper($key);

        return $key_;
    }

    /**
     * @return bool|int
     * @throws \ReflectionException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function save()
    {
        if ($this->validate()) {
            $attributes = $this->attributes;
            $data = [];
            foreach ($attributes as $attribute => $value) {
                $data[] = new SystemSetting([
                    'key' => $this->_generateKey($this, $attribute),
                    'value' => $value
                ]);
            }

            $fields = SystemSetting::getTableSchema()->columnNames;
            SystemSetting::deleteAll(['key' => ArrayHelper::getColumn($data, 'key')]);

            if (SystemSetting::validateMultiple($data, $fields)) {
                return Yii::$app->db->createCommand()
                    ->batchInsert(SystemSetting::tableName(), $fields, $data)
                    ->execute();
            }
        }

        return FALSE;
    }

    /**
     * @param $class
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function model($class)
    {
        $all_values = $this->_setting;
        $model = new $class;
        foreach ($model->attributes as $key => $attribute) {
            $setting_key = $this->_generateKey($model, $key);
            $model->$key = $all_values[$setting_key] ?? NULL;
        }

        return $model;
    }
}
