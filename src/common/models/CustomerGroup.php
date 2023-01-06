<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Customer[] $customers
 */
class CustomerGroup extends ActiveRecord
{
    public static $alias = 'customerGroup';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'validateDuplicate'],
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name'),
            'description' => Yii::t('common', 'Description'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::class, ['group_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function states()
    {
        $records = self::find()->active()->all();
        return ArrayHelper::map($records, 'id', 'name');
    }
}
