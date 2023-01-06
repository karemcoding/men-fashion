<?php

namespace common\models;

use common\util\Status;
use Exception;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%order_returns}}".
 *
 * @property int $id
 * @property int|null $order_id
 * @property string|null $note
 * @property string|null $remark
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Order $order
 */
class OrderReturns extends ActiveRecord
{
    const SCENARIO_UPDATE = 'update';

    const STATUS_APPLIED = Status::STATUS_ACTIVE;
    const STATUS_CANCEL = Status::STATUS_INACTIVE;
    const STATUS_FINISHED = 20;

    public static $alias = 'orderReturns';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_returns}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['note', 'status'], 'required'],
            [['order_id', 'status',
                'created_by', 'updated_by',
                'created_at', 'updated_at'], 'integer'],
            [['note', 'remark'], 'string', 'max' => 1000],
            [['order_id'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => Order::class,
                'targetAttribute' => ['order_id' => 'id']],
            [['remark'], 'required', 'on' => [self::SCENARIO_UPDATE]],
            [['status'], 'in', 'range' => [self::STATUS_APPLIED, self::STATUS_FINISHED, self::STATUS_CANCEL]],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE] = ['status', 'remark'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã'),
            'order_id' => Yii::t('common', 'Đơn hàng'),
            'note' => Yii::t('common', 'Ghi chú'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'remark' => Yii::t('common', 'Remark'),
        ];
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
     * @return string
     */
    public static function viewStatus($status)
    {
        if ($status == self::STATUS_APPLIED) {
            return Html::tag('span', Yii::t('common', 'Chấp nhận'),
                ['class' => 'badge badge-soft-primary']);
        }
        if ($status == self::STATUS_FINISHED) {
            return Html::tag('span', Yii::t('common', 'Hoàn thành'),
                ['class' => 'badge badge-soft-success']);
        }
        return Html::tag('span', Yii::t('common', 'Hủy'),
            ['class' => 'badge badge-soft-danger']);
    }

    /**
     * @return array
     */
    public static function statusForSelect($selected)
    {
        $status = [self::STATUS_CANCEL, self::STATUS_FINISHED];
        $title = function ($value) {
            if ($value == self::STATUS_CANCEL) {
                return Yii::t('common', 'Hoàn thành');
            }
            return Yii::t('common', 'Hủy');
        };
        foreach ($status as $item) {
            $result[] = [
                'id' => $item,
                'text' => self::viewStatus($item),
                'html' => self::viewStatus($item),
                'selected' => $selected == $item,
                'title' => $title($item),
            ];
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function saveAsStatus()
    {
        if ($this->status == self::STATUS_FINISHED) {
            $transaction = self::getDb()->beginTransaction();
            try {
                $this->save();
                $order = $this->order;
                $order->status = Order::ROLLBACK;
                $order->remark = $this->note;
                $order->save();
                $transaction->commit();
                return TRUE;
            } catch (Exception $exception) {
                throwException($exception);
                $transaction->rollBack();
                return FALSE;
            }
        }
        return $this->save();
    }
}
