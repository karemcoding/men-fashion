<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%product_supplier}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $tel
 * @property string|null $fax
 * @property string|null $address
 * @property string|null $description
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ProductSupplier extends ActiveRecord
{
    public static $alias = 'productSupplier';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_supplier}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tel', 'name'], 'required'],
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name',  'address'], 'string', 'max' => 255],
            [['tel', 'fax'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã nhà cung cấp'),
            'name' => Yii::t('common', 'Tên'),
            'tel' => Yii::t('common', 'Số điện thoại'),
            'fax' => Yii::t('common', 'Số fax'),
            'address' => Yii::t('common', 'Địa chỉ'),
            'description' => Yii::t('common', 'Mô tả'),
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
        $temp = [NULL => Yii::t('common', 'No Supplier')];
        $state = ArrayHelper::map(self::find2All(), 'id', 'name');
        return ArrayHelper::merge($temp, $state);
    }
}
