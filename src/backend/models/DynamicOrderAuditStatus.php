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
 * Class DynamicOrderAuditStatus
 * @package backend\models
 */
class DynamicOrderAuditStatus extends DynamicOrderAudit
{
    public $status;

    /**
     * @param OrderHistory[] $orderHistories
     * @return array
     */
    public static function generate(array $orderHistories)
    {
        $temp = [];
        foreach ($orderHistories as $item) {
            if (in_array($item->operation, [AuditBehavior::OP_INSERT, AuditBehavior::OP_UPDATE])) {
                if ($item->attribute == 'status' || $item->attribute == 'remark') {
                    $temp[$item->timestamp][$item->attribute] = $item->new;
                    $temp[$item->timestamp]['identity'] = $item->identity;
                    $temp[$item->timestamp]['identity_id'] = $item->identity_id;
                }
            }
        }
        $result = [];
        $tempStaff = [];
        foreach ($temp as $key => $item) {
            if (!empty($item['status'])) {
                $obj = new self([
                    'time' => $key,
                    'status' => $item['status'] ?? NULL,
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