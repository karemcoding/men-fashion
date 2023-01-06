<?php

namespace backend\models;

use yii\base\BaseObject;

class DynamicOrderAudit extends BaseObject
{
    public $time;
    public $user_id;
    public $staff;
    public $remark;
}