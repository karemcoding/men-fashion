<?php

use yii\db\Migration;

/**
 * Class m210422_160813_order_detail
 */
class m210422_160813_order_detail extends Migration
{
    const TABLE_NAME = '{{%order_detail}}';

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->double(),
            'unit_price' => $this->double(),
            'amount' => $this->double(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_order_detail_product_id',
            self::TABLE_NAME,
            'product_id',
            '{{%product}}',
            'id',
            'NO ACTION',
            'NO ACTION');
        $this->addForeignKey(
            'fk_order_detail_order_id',
            self::TABLE_NAME,
            'order_id',
            '{{%order}}',
            'id',
            'NO ACTION',
            'NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('fk_order_detail_product_id', self::TABLE_NAME);
        $this->dropForeignKey('fk_order_detail_order_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
