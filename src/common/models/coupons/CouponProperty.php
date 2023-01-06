<?php

namespace common\models\coupons;

use common\models\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%coupon_property}}".
 *
 * @property int|null $coupon_id
 * @property string|null $key
 * @property string|null $value
 *
 * @property CouponModel $coupon
 */
class CouponProperty extends ActiveRecord
{
    public static $alias = 'couponProperty';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%coupon_property}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['coupon_id'], 'integer'],
            [['value'], 'string'],
            [['key'], 'string', 'max' => 255],
            [['key'], 'unique'],
            [['coupon_id', 'key'],
                'unique',
                'targetAttribute' => ['coupon_id', 'key']],
            [['coupon_id'], 'exist',
                'skipOnError' => TRUE,
                'targetClass' => CouponModel::class,
                'targetAttribute' => ['coupon_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'coupon_id' => Yii::t('common', 'Coupon ID'),
            'key' => Yii::t('common', 'Key'),
            'value' => Yii::t('common', 'Value'),
        ];
    }

    /**
     * Gets query for [[Coupon]].
     *
     * @return ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(CouponModel::class, ['id' => 'coupon_id']);
    }
}
