<?php
/**
 * @author ANDY <ltanh1194@gmail.com>
 * @date 2:38 PM 6/27/2021
 * @projectName baseProject by ANDY
 */

namespace backend\models;


use common\behaviors\audit\AuditBehavior;
use common\models\OrderHistory;

/**
 * Class DynamicOrderAuditPaymentStatus
 * @package backend\models
 */
class DynamicOrderAuditPaymentStatus extends DynamicOrderAudit
{
    public $payment_status;
    public $payment_method;

    /**
     * @param OrderHistory[] $orderHistories
     * @return array
     */
    public static function generate(array $orderHistories)
    {
        $temp = [];
        foreach ($orderHistories as $item) {
            if (in_array($item->operation, [AuditBehavior::OP_INSERT, AuditBehavior::OP_UPDATE])) {
                if ($item->attribute == 'payment_status'
                    || $item->attribute == 'remark'
                    || $item->attribute == 'payment_method') {
                    $temp[$item->timestamp][$item->attribute] = $item->new;
                    $temp[$item->timestamp]['identity'] = $item->identity;
                    $temp[$item->timestamp]['identity_id'] = $item->identity_id;
                }
            }
        }
        $result = [];
        $tempStaff = [];
        foreach ($temp as $key => $item) {
            if (!empty($item['payment_status']) || !empty($item['payment_method'])) {
                $obj = new self([
                    'time' => $key,
                    'payment_status' => $item['payment_status'] ?? NULL,
                    'payment_method' => $item['payment_method'] ?? NULL,
                    'remark' => $item['remark'] ?? NULL,
                    'user_id' => $item['identity_id'] ?? NULL,
                ]);
                if ($item['identity'] == 'backend\models\User') {
                    if (!in_array($item['identity_id'], array_keys($tempStaff))) {
                        $tempStaff[$item['identity_id']] = $item['identity']::findOne($item['identity_id'])->name;
                    }
                    $obj->staff = $tempStaff[$item['identity_id']];
                }
                $result[] = $obj;
            }
        }
        return $result;
    }
}