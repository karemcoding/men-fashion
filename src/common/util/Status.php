<?php

namespace common\util;

use Yii;

/**
 * Class Status
 *
 * @package common\util
 */
class Status
{
    const STATUS_DELETED = -10;

    const STATUS_INACTIVE = 0;

    const STATUS_ACTIVE = 10;

    /**
     * @return array
     */
    public static function states(): array
    {
        return [
            self::STATUS_ACTIVE => Yii::t('common', 'Kích hoạt'),
            self::STATUS_INACTIVE => Yii::t('common', 'Chưa kích hoạt')
        ];
    }
}