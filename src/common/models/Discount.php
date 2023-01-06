<?php

namespace common\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "{{%discount}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property float|null $default_value
 * @property int|null $from
 * @property int|null $to
 * @property int|null $type
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Discount extends ActiveRecord
{

    public static $alias = 'discount';

    const TYPE_RAW = 20;
    const TYPE_PERCENT = 30;

    public $from_date;
    public $to_date;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%discount}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'default_value', 'type', 'from_date', 'to_date'], 'required'],
            [['default_value'], 'number'],
            [['from', 'to', 'type', 'status',
                'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description', 'from_date', 'to_date'], 'string', 'max' => 255],
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
            'default_value' => Yii::t('common', 'Default Value'),
            'from' => Yii::t('common', 'From'),
            'from_date' => Yii::t('common', 'From'),
            'to' => Yii::t('common', 'To'),
            'to_date' => Yii::t('common', 'To'),
            'type' => Yii::t('common', 'Type'),
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
    public function getProductDiscounts()
    {
        return $this->hasMany(ProductDiscount::class, ['discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])
            ->via('productDiscounts');
    }

    /**
     * @return bool
     */
    public function saveAs()
    {
        $time = function ($date) {
            $datetime = DateTime::createFromFormat('d/m/Y', $date);
            return $datetime->getTimestamp();
        };
        $this->from = $time($this->from_date);
        $this->to = $time($this->to_date);
        if ($this->to < $this->from) {
            $this->addError('to_date', Yii::t('common', "\"To date\" must be greater than \"From date\""));
            return FALSE;
        }
        return $this->save();
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->from_date = date('d/m/Y', $this->from);
        $this->to_date = date('d/m/Y', $this->to);
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
