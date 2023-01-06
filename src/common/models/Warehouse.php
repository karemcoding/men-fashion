<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%warehouse}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $address
 * @property string|null $description
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Inventory[] $inventories
 * @property Product[] $products
 * @property-read InventoryHistory[] $inventoryHistory
 */
class Warehouse extends ActiveRecord
{
    public static $alias = 'warehouse';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%warehouse}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'address', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã kho'),
            'name' => Yii::t('common', 'Tên'),
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
     * Gets query for [[Inventories]].
     *
     * @return ActiveQuery
     */
    public function getInventories()
    {
        return $this->hasMany(Inventory::class, ['warehouse_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return ActiveQuery
     */
    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])->via('inventories');
    }

    /**
     * @return ActiveQuery
     */
    public function getInventoryHistory(): ActiveQuery
    {
        return $this->hasMany(InventoryHistory::class, ['warehouse_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function select()
    {
        $records = self::find()->active()->all();
        return ArrayHelper::map($records, 'id', 'name');
    }
}
