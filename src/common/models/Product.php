<?php

namespace common\models;

use common\behaviors\audit\AuditBehavior;
use common\behaviors\status\StatusBehavior;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property int $id
 * @property int $parent_id
 * @property string|null $sku
 * @property int|null $category_id
 * @property string|null $name
 * @property float|null $price
 * @property string|null $unit
 * @property int|null $status
 * @property float|null $inventory
 * @property float|null $sold
 * @property int|null $supplier_id
 * @property int|null $brand_id
 * @property string|null $description
 * @property int|null $hot
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $thumbnail
 * @property string|null $gallery
 *
 * @property Cart[] $carts
 * @property Customer[] $customersCart
 * @property Favorite[] $favorites
 * @property Customer[] $customersFavorite
 * @property Feedback[] $feedbacks
 * @property Inventory[] $inventories
 * @property Warehouse[] $warehouses
 * @property OrderDetail[] $orderDetails
 * @property ProductCategory $category
 * @property-read InventoryHistory[] $inventoryHistory
 * @property-read ProductBrand $brand
 * @property-read ProductSupplier $supplier
 * @property-read Discount[] $discounts
 * @property-read ProductDiscount[] $productDiscounts
 * @property string $slug [varchar(255)]
 */
class Product extends ActiveRecord
{
    public static $alias = 'product';
    const EXTENSIONS = ['jpg', 'png', 'jpeg'];
    public $image;
    public $images;
    public $discount_price = NULL;
    /**
     * @var null|ProductDiscount
     */
    public $discountObj = NULL;

    public function behaviors()
    {
        $behavior = [
            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'ensureUnique' => TRUE,
            ],
            'status' => [
                'class' => StatusBehavior::class,
            ],
            'history' => [
                'class' => AuditBehavior::class,
            ],
        ];
        return ArrayHelper::merge(parent::behaviors(), $behavior);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'parent_id', 'status', 'created_by',
                'updated_by', 'created_at', 'updated_at', 'hot'], 'integer'],
            [['price'], 'number'],
            [['name', 'unit', 'sku', 'size'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => ProductCategory::class,
                'targetAttribute' => ['category_id' => 'id']],
            [['category_id', 'name', 'price', 'sku', 'size'], 'required'],
            [['description'], 'string', 'max' => 1000],
            [['thumbnail', 'gallery'], 'string'],
            [['images'], 'file',
                'skipOnEmpty' => TRUE,
                'skipOnError' => TRUE,
                'extensions' => self::EXTENSIONS,
                'maxFiles' => 6,
                'maxSize' => 1048576],
            [['image'], 'file',
                'skipOnEmpty' => TRUE,
                'skipOnError' => TRUE,
                'extensions' => self::EXTENSIONS,
                'maxSize' => 1048576],
            [
                'sku',
                'validateDuplicate',
            ],
            [['supplier_id'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => ProductSupplier::class,
                'targetAttribute' => ['supplier_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => ProductBrand::class,
                'targetAttribute' => ['brand_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => TRUE, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã'),
            'parent_id' => Yii::t('common', 'Sản phẩm đại diện'),
            'category_id' => Yii::t('common', 'Danh mục'),
            'name' => Yii::t('common', 'Tên'),
            'price' => Yii::t('common', 'Giá gốc'),
            'unit' => Yii::t('common', 'Đơn vị'),
            'status' => Yii::t('common', 'Trạng thái'),
            'description' => Yii::t('common', 'Mô tả'),
            'hot' => Yii::t('common', 'Nổi bật'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'thumbnail' => Yii::t('common', 'Ảnh đại diện'),
            'gallery' => Yii::t('common', 'Thư viện ảnh'),
            'sku' => Yii::t('common', 'SKU'),
            'supplier_id' => Yii::t('common', 'Nhà cung cấp'),
            'brand_id' => Yii::t('common', 'Brand'),
            'discount_price' => Yii::t('common', 'Giá'),
            'size' => Yii::t('common', 'Kích cỡ'),
            'status' => Yii::t('common', 'Trạng thái'),
            'inventory' => Yii::t('common', 'Tồn kho'),
            'image' => Yii::t('common', 'Hình đại diện'),
            'images' => Yii::t('common', 'Các hình ảnh')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     * Tất cả người dùng thích sản phẩm này
     */
    public function getCustomersCart()
    {
        return $this->hasMany(Customer::class, ['id' => 'customer_id'])
            ->viaTable('{{%cart}}',
                ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCustomersFavorite()
    {
        return $this->hasMany(Customer::class, ['id' => 'customer_id'])
            ->viaTable('{{%favorite}}',
                ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Inventories]].
     *
     * @return ActiveQuery
     */
    public function getInventories()
    {
        return $this->hasMany(Inventory::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::class, ['id' => 'warehouse_id'])
            ->viaTable('{{%inventory}}', ['product_id' => 'id']);
    }

    /**
     * Gets query for [[OrderDetails]].
     *
     * @return ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetail::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductCategory::class, ['id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getInventoryHistory()
    {
        return $this->hasMany(InventoryHistory::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(ProductSupplier::class, ['id' => 'supplier_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(ProductBrand::class, ['id' => 'brand_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductDiscounts()
    {
        return $this->hasMany(ProductDiscount::class, ['product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDiscounts()
    {
        return $this->hasMany(Discount::class, ['id' => 'discount_id'])
            ->via('productDiscounts');
    }

    /**
     * @param $customerId
     * @return array|Product[]|ActiveRecord[]
     */
    public static function findWithCustomerFavorite($customerId)
    {
        return static::find()
            ->selectDefault()
            ->withCategory()
            ->addSelect(['in_cart' => 'cartOfFavorite.quantity'])
            ->joinWith(['favorites favorites' => function (ActiveQuery $q) {
                $q->joinWith('cartOfFavorite cartOfFavorite', FALSE);
            }], FALSE)
            ->andWhere(['favorites.customer_id' => $customerId])
            ->asArray()->all();
    }

    /**
     * {@inheritdoc}
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->setDiscountPrice();
    }

    public function setDiscountPrice()
    {
        if ($this->productDiscounts) {
            $temp = $this->productDiscounts;
            usort($temp, function ($first, $second) {
                return $first->discount_price <=> $second->discount_price;
            });
            $this->discount_price = $temp[0]->discount_price;
            $this->discountObj = $temp[0];
        }
    }

    /**
     * @return float|null
     * Giá này là giá người dùng phải thanh toán (sau khi khuyến mãi...)
     * Còn giá mặc định là giá của sản phẩm
     */
    public function orderPrice()
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * @param $quantity
     * @return int
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function stockIn($quantity): int
    {
        $inventory = $this->inventory ?? 0;
        if ($quantity > 0) {
            $params = ['inventory' => $inventory + $quantity];
            try {
                return self::getDb()->createCommand()
                    ->update(self::tableName(), $params, ['id' => $this->id])
                    ->execute();
            } catch (Exception $exception) {
                throw $exception;
            }
        }
        Yii::$app->session->addFlash('error', Yii::t('common',
            '{0} wrong quantity',
            [$this->name]));
        throw new Exception();
    }

    /**
     * @param $quantity
     * @return int
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public function stockOut($quantity): int
    {
        $inventory = $this->inventory ?? 0;
        if ($quantity > 0 && $quantity <= $inventory) {
            $params = ['inventory' => $inventory - $quantity];
            try {
                return self::getDb()->createCommand()
                    ->update(self::tableName(), $params, ['id' => $this->id])
                    ->execute();
            } catch (Exception $exception) {
                throw $exception;
            }
        }
        $message = '';
        if ($quantity <= 0) {
            $message = '{0} wrong quantity';
        } elseif ($quantity > $inventory) {
            $message = '{0} not enough quantity';
        }
        Yii::$app->session->addFlash('error', Yii::t('common', $message, [$this->name]));
        throw new Exception();
    }
}
