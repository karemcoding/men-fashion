<?php

use yii\db\Migration;

/**
 * Class m210701_160407_add_col_product_discount_id
 */
class m210701_160407_add_col_product_discount_id extends Migration
{
    const TABLE_NAME = '{{%order_detail}}';

    public function up()
    {
        $this->addColumn(self::TABLE_NAME, 'product_discount_id',
            $this->integer()->after('product_id'));

        $this->addForeignKey(
            'fk_order_detail_product_discount_id',
            self::TABLE_NAME,
            'product_discount_id',
            '{{%product_discount}}',
            'id',
            'SET NULL',
            'NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('fk_order_detail_product_discount_id', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'product_discount_id');
    }
}
