<?php

namespace common\models;

use Exception;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%inventory}}".
 *
 * @property int $product_id
 * @property int $warehouse_id
 * @property float|null $quantity
 *
 * @property Product $product
 * @property Warehouse $warehouse
 */
class Inventory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%inventory}}';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['product_id', 'warehouse_id'], 'required'],
            [['product_id', 'warehouse_id'], 'integer'],
            [['quantity'], 'number', 'min' => 0],
            [['product_id', 'warehouse_id'], 'unique', 'targetAttribute' => ['product_id', 'warehouse_id']],
            [['product_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Product::class,
                'targetAttribute' => ['product_id' => 'id']],
            [['warehouse_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Warehouse::class,
                'targetAttribute' => ['warehouse_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'product_id' => Yii::t('common', 'Mã sản phẩm'),
            'warehouse_id' => Yii::t('common', 'Mã kho'),
            'quantity' => Yii::t('common', 'Số lượng'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getWarehouse(): ActiveQuery
    {
        return $this->hasOne(Warehouse::class, ['id' => 'warehouse_id']);
    }

    /**
     * @param $productId
     * @param $storeId
     * @param $quantity
     * @return bool
     * @throws Exception
     */



    public static function stockIn($productId, $storeId, $quantity): bool
    {
        if ($quantity > 0) {

            $condition = ['product_id' => $productId, 'warehouse_id' => $storeId];
            $model = self::findOne($condition);
            if ($model) {
                $model->quantity = $model->quantity + $quantity;
            } else {
                $model = new self($condition);
                $model->quantity = $quantity;
            }
            if ($model->save()) return true;
        }
        Yii::$app->session->addFlash('error', Yii::t('common', 'Quantity Wrong'));
        throw new Exception();
    }

    /**
     * @param $productId
     * @param $storeId
     * @param $quantity
     * @return bool
     * @throws Exception
     */
    public static function stockOut($productId, $storeId, $quantity): bool
    {
        $condition = ['product_id' => $productId, 'warehouse_id' => $storeId];
        $model = self::findOne($condition);
        if ($model && $quantity > 0 && $quantity <= $model->quantity) {
            $model->quantity = $model->quantity - $quantity;
            if ($model->save()) return true;
        }
        Yii::$app->session->addFlash('error', Yii::t('common', 'Quantity Wrong'));
        throw new Exception();
    }
}
