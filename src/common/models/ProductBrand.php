<?php

namespace common\models;

use common\util\Status;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%product_brand}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ProductBrand extends ActiveRecord
{
    public static $alias = 'productBrand';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%product_brand}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['status'], 'in', 'range' => [Status::STATUS_ACTIVE, Status::STATUS_INACTIVE, Status::STATUS_DELETED]],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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
     * @return array|ProductBrand[]|\yii\db\ActiveRecord[]
     */
    public static function find2All()
    {
        return self::find()->active()->all();
    }

    /**
     * @return array
     */
    public static function select2()
    {
        $temp = [NULL => Yii::t('common', 'No Brand')];
        $state = ArrayHelper::map(self::find2All(), 'id', 'name');
        return ArrayHelper::merge($temp, $state);
    }
}
