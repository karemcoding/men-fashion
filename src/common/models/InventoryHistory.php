<?php

namespace common\models;

use Exception;
use Throwable;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%stock_in}}".
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $warehouse_id
 * @property float|null $inventory
 * @property float|null $quantity
 * @property int|null $type
 * @property string|null $ref
 * @property int|null $reason_id
 * @property string|null $description
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Product $product
 * @property-read InventoryReason $reason
 * @property-read array $reasonOfRecord
 * @property Warehouse $warehouse
 */
class InventoryHistory extends ActiveRecord
{
    const PLUS = 20;
    const MINUS = 30;

    public static $alias = 'inventoryLog';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%inventory_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity', 'type', 'warehouse_id', 'reason_id'], 'required'],
            [['product_id', 'warehouse_id',
                'created_by', 'updated_by',
                'created_at', 'updated_at', 'status'], 'integer'],
            [['type'], 'in', 'range' => self::state()],
            [['inventory', 'quantity', 'reason_id'], 'integer'],
            [['description', 'ref','size'], 'string'],
            [['product_id'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => Product::class,
                'targetAttribute' => ['product_id' => 'id']],
            [['warehouse_id'], 'exist',
                'skipOnError' => TRUE,
                'targetClass' => Warehouse::class,
                'targetAttribute' => ['warehouse_id' => 'id']],
            [['reason_id'], 'exist',
                'skipOnError' => TRUE,
                'targetClass' => InventoryReason::class,
                'targetAttribute' => ['reason_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'product_id' => Yii::t('common', 'Sản phẩm'),
            'warehouse_id' => Yii::t('common', 'Kho'),
            'inventory' => Yii::t('common', 'Tồn kho'),
            'quantity' => Yii::t('common', 'Số lượng'),
            'size' => Yii::t('common', 'Kích thước'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'reason_id' => Yii::t('common', 'Lý do'),
            'ref' => Yii::t('common', 'Liên hệ'),
            'type' => Yii::t('common', 'Loại')
        ];
    }

    public function attributeHints()
    {
        return [
            'ref' => Yii::t('common', 'Select Reference Oder to ship the goods'),
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Warehouse]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Warehouse::class, ['id' => 'warehouse_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReason()
    {
        return $this->hasOne(InventoryReason::class, ['id' => 'reason_id']);
    }

    /**
     * @return array
     */
    public static function state(): array
    {
        return [self::PLUS, self::MINUS];
    }

    /**
     * @param InventoryHistory $model
     * @return array
     */
    public static function typeForSelect(self $model)
    {
        $status = self::state();
        foreach ($status as $item) {
            $result[] = [
                'id' => $item,
                'text' => self::generateTypeView()[$item][0],
                'html' => self::generateTypeView()[$item][0],
                'selected' => $model->type == $item,
                'title' => self::generateTypeView()[$item][1],
            ];
        }
        return $result ?? [];
    }

    /**
     * @return array
     */
    public static function generateTypeView(): array
    {
        $view = function ($title, $class) {
            return [
                Html::tag('span', Yii::t('common',
                    Yii::t('common', "{title}", ['title' => $title])),
                    ['class' => "badge bg-$class text-white"]),
                $title,
            ];
        };
        $result[self::PLUS] = $view('Stock In', 'success');
        $result[self::MINUS] = $view('Stock Out', 'info');
        return $result;
    }

    /**
     * @param Product $product
     * @return false
     */
    public function add(Product $product): bool
    {
        if (!$this->validate()) {
            return FALSE;
        }
        $conn = self::getDb();
        $transaction = $conn->beginTransaction();
        try {
            if ($this->type == self::PLUS) {
                $product->stockIn($this->quantity);
                Inventory::stockIn($product->id, $this->warehouse_id, $this->quantity);
            }
            if ($this->type == self::MINUS) {
                $product->stockOut($this->quantity);
                Inventory::stockOut($product->id, $this->warehouse_id, $this->quantity);
            }
            $insert = $this->save();
            $transaction->commit();
            return $insert;
        } catch (Exception $e) {
            $transaction->rollBack();
        } catch (Throwable $e) {
            $transaction->rollBack();
        }
        return FALSE;
    }

    /**
     * @return mixed
     */
    public function getReasonOfRecord()
    {
        $reason = InventoryReason::select2NotUseStrict();
        return $reason[$this->reason_id];
    }
}
