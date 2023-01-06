<?php

namespace common\models\coupons;

use common\models\ActiveRecord;
use Exception;
use Symfony\Component\Yaml\Yaml;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $type
 * @property string|null $model_class
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property-read OrderCoupon[] $orderCoupons
 * @property CouponProperty[] $couponProperties
 */
class CouponModel extends ActiveRecord
{
    public static $alias = 'coupon';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status',
                'created_by', 'updated_by',
                'created_at', 'updated_at',
            ], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * Gets query for [[CouponProperties]].
     *
     * @return ActiveQuery
     */
    public function getCouponProperties()
    {
        return $this->hasMany(CouponProperty::class, ['coupon_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrderCoupons()
    {
        return $this->hasMany(OrderCoupon::class, ['coupon_id' => 'id']);
    }

    /**
     * @param $condition
     * @return null|static
     */
    public static function findOneWithProperty($condition)
    {
        $obj = static::find()
            ->joinWith(['couponProperties'])
            ->andWhere($condition)
            ->one();
        $propGen = function () use ($obj) {
            foreach ($obj->couponProperties as $property) {
                yield $property->key => $property->value;
            }
        };
        foreach ($propGen() as $prop => $value) {
            $obj->$prop = $value;
        }
        return $obj;
    }

    /**
     * @return CouponModel|CouponValue
     * Từ CouponModel chuyển thành Class con
     */
    public function autoConvert()
    {
        /** @var static $model */
        $model = new $this->model_class;
        $model->setAttributes($this->attributes, FALSE);
        foreach ($this->relatedRecords as $key => $relatedRecord) {
            $model->populateRelation($key, $relatedRecord);
        }
        return $model->convert();
    }

    /**
     * @return $this
     */
    public function convert()
    {
        $propGen = function () {
            foreach ($this->couponProperties as $property) {
                yield $property->key => $property->value;
            }
        };
        foreach ($propGen() as $prop => $value) {
            $this->$prop = $value;
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function store(): bool
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($this->save()) {
                    $propGen = function () {
                        foreach (get_object_vars($this) as $attribute => $value) {
                            yield new CouponProperty([
                                'coupon_id' => $this->id,
                                'key' => $attribute,
                                'value' => $value,
                            ]);
                        }
                    };
                    CouponProperty::deleteAll(['coupon_id' => $this->id]);
                    Yii::$app->db->createCommand()
                        ->batchInsert(CouponProperty::tableName(),
                            ['coupon_id', 'key', 'value',], $propGen())
                        ->execute();
                    $transaction->commit();
                    return TRUE;
                }
            } catch (Exception $exception) {
                $transaction->rollBack();
                return FALSE;
            }
        }
        return FALSE;
    }

    /**
     * @return array|mixed
     */
    public function findTemplates()
    {
        $path = Yii::getAlias("@common/models/coupons/coupon_template.yml");
        if (file_exists($path)) {
            return Yaml::parse(file_get_contents($path));
        }
        return [];
    }
}
