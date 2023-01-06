<?php

namespace common\models;

use common\util\Status;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;

/**
 * This is the model class for table "{{%product_discount}}".
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $discount_id
 * @property float|null $origin_price
 * @property float|null $discount_price
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Product $product
 * @property Discount $discount
 */
class ProductDiscount extends ActiveRecord
{
    public static $alias = 'productDiscount';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_discount}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['discount_price'], 'required'],
            [['product_id', 'discount_id',
                'status', 'created_by', 'updated_by',
                'created_at', 'updated_at'], 'integer'],
            [['product_id'], 'exist',
                'skipOnError' => TRUE,
                'targetClass' => Product::class,
                'targetAttribute' => ['product_id' => 'id']],
            [['discount_id'], 'exist',
                'skipOnError' => TRUE,
                'targetClass' => Discount::class,
                'targetAttribute' => ['discount_id' => 'id']],
            [['product_id', 'discount_id'], 'unique',
                'skipOnError' => TRUE,
                'targetClass' => self::class,
                'targetAttribute' => ['product_id', 'discount_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã'),
            'product_id' => Yii::t('common', 'Mã sản phẩm'),
            'discount_id' => Yii::t('common', 'Mã chương trình giảm giá'),
            'origin_price' => Yii::t('common', 'Giá gốc'),
            'discount_price' => Yii::t('common', 'Giá giảm'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
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
     * Gets query for [[Discount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiscount()
    {
        return $this->hasOne(Discount::class, ['id' => 'discount_id']);
    }

    /**
     * @param $products
     * @param $discount
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function batchSave($products, $discount)
    {
        $allProducts = Product::find()->andWhere([Product::$alias . '.id' => $products])->indexBy('id')->all();
        $discountObj = Discount::findOne($discount);
        $allProductDiscount = ProductDiscount::find()
            ->select(['product_id'])
            ->andWhere(['discount_id' => $discount])
            ->indexBy('product_id')
            ->asArray()
            ->all();
        if (!$discountObj) return NULL;
        $transaction = self::getDb()->beginTransaction();
        try {
            $gen = function () use ($products, $discount, $allProducts, $discountObj, $allProductDiscount) {
                foreach ($products as $item) {
                    $originPrice = $allProducts[$item]->price;
                    if ($discountObj->type == Discount::TYPE_PERCENT) {
                        $raw = ($discountObj->default_value / 100) * $originPrice;
                        $discountPrice = ceil($originPrice - $raw);
                    } else {
                        $discountPrice = $originPrice - $discountObj->default_value;
                    }
                    if (!in_array($item, array_keys($allProductDiscount))) {
                        yield new self([
                            'product_id' => $item,
                            'discount_id' => $discount,
                            'origin_price' => $allProducts[$item]->price,
                            'discount_price' => $discountPrice,
                            'created_at' => time(),
                            'updated_at' => time(),
                            'created_by' => Yii::$app->user->identity->getId(),
                            'updated_by' => Yii::$app->user->identity->getId(),
                            'status' => Status::STATUS_ACTIVE,
                        ]);
                    } else {
                        $data = [
                            'product_id' => $item,
                            'discount_id' => $discount,
                        ];
                        ProductDiscount::getDb()
                            ->createCommand()
                            ->upsert(ProductDiscount::tableName(),
                                $data, [
                                    'origin_price' => $allProducts[$item]->price,
                                    'discount_price' => $discountPrice,
                                    'created_at' => time(),
                                    'updated_at' => time(),
                                    'created_by' => Yii::$app->user->identity->getId(),
                                    'updated_by' => Yii::$app->user->identity->getId(),
                                    'status' => Status::STATUS_ACTIVE,
                                ])->execute();
                    }
                }
            };
            ProductDiscount::getDb()
                ->createCommand()
                ->batchInsert(ProductDiscount::tableName(),
                    self::getTableSchema()->columnNames,
                    $gen())->execute();
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
        }

    }
}
