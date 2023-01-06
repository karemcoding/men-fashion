<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%inventory_reason}}".
 *
 * @property int $id
 * @property string|null $reason
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class InventoryReason extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%inventory_reason}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['reason'], 'string', 'max' => 255],
            [['reason'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã'),
            'reason' => Yii::t('common', 'Lý do'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
        ];
    }

    /**
     * @param bool $useStrict
     * @return array|InventoryReason[]|\yii\db\ActiveRecord[]
     */
    public static function find2All($useStrict = true)
    {
        $query = self::find();
        if ($useStrict) {
            $query->active();
        }
        return $query->all();
    }

    /**
     * @return array
     */
    public static function select2()
    {
        $temp = [NULL => Yii::t('common', 'Chọn lý do')];
        $state = ArrayHelper::map(self::find2All(), 'id', 'reason');
        return ArrayHelper::merge($temp, $state);
    }

    /**
     * @return array
     */
    public static function select2NotUseStrict()
    {
        return ArrayHelper::map(self::find2All(false), 'id', 'reason');
    }
}
