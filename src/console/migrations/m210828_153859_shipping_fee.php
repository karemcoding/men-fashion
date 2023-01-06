<?php

use yii\db\Migration;

/**
 * Class m210828_153859_shipping_fee
 */
class m210828_153859_shipping_fee extends Migration
{
    const TABLE_NAME = '{{%fee}}';

    public function up()
    {
        $this->addColumn(self::TABLE_NAME, 'shipping_fee', $this->tinyInteger()->defaultValue(-10));
    }

    public function down()
    {
        return TRUE;
    }
}
