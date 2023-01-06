<?php

namespace common\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;

/**
 * This is the model class for table "{{%order_fee}}".
 *
 * @property int|null $order_id
 * @property int|null $fee_id
 * @property float|null $order_subtotal
 * @property float|null $fee_value
 *
 * @property Fee $fee
 * @property Order $order
 */
class OrderFee extends ActiveRecord
{

    public static $alias = 'orderFee';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_fee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'fee_id'], 'integer'],
            [['order_subtotal', 'fee_value'], 'number'],
            [['fee_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Fee::className(), 'targetAttribute' => ['fee_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => TRUE, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('common', 'Order ID'),
            'fee_id' => Yii::t('common', 'Fee ID'),
            'order_subtotal' => Yii::t('common', 'Order Subtotal'),
            'fee_value' => Yii::t('common', 'Fee Value'),
        ];
    }

    /**
     * Gets query for [[Fee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFee()
    {
        return $this->hasOne(Fee::class, ['id' => 'fee_id']);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * @param array $data
     * @return int
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function add($data = [])
    {
        $attributes = self::getTableSchema()->getColumnNames();

        if (self::validateMultiple($data, $attributes)) {
            return Yii::$app->db->createCommand()
                ->batchInsert(self::tableName(), $attributes, $data)->execute();
        }
        throw new Exception('Order detail invalid');
    }
}
