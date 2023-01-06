<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%fee}}".
 *
 * @property int $id
 * @property string|null $name
 * @property float|null $value
 * @property int|null $type
 * @property string|null $remark
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property OrderFee[] $orderFees
 */
class Fee extends ActiveRecord
{

    public static $alias = 'fee';

    const TYPE_RAW = 20;
    const TYPE_PERCENT = 30;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%fee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'value', 'type'], 'required'],
            [['value'], 'number'],
            [['type', 'status', 'created_by',
                'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['type'], 'in', 'range' => [self::TYPE_RAW, self::TYPE_PERCENT]],
            [['name', 'remark'], 'string', 'max' => 255],
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
            'value' => Yii::t('common', 'Default Value'),
            'type' => Yii::t('common', 'Type'),
            'remark' => Yii::t('common', 'Remark'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
        ];
    }

    /**
     * Gets query for [[OrderFees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFees()
    {
        return $this->hasMany(OrderFee::class, ['fee_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function selectType()
    {
        return [
            self::TYPE_RAW => Yii::t('common', 'Raw Money ($)'),
            self::TYPE_PERCENT => Yii::t('common', 'Percent (%)'),
        ];
    }
}
