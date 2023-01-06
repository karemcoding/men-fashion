<?php

use yii\db\Migration;

/**
 * Class m210422_160636_cart
 */
class m210422_160636_cart extends Migration
{
    const TABLE_NAME = '{{%cart}}';

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'product_id' => $this->integer()->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'quantity' => $this->double()->defaultValue(1),
            'created_at' => $this->integer(),
        ], $tableOptions);

        $this->addPrimaryKey('PRIMARY_KEY',
            self::TABLE_NAME, ['product_id', 'customer_id']);

        $this->addForeignKey(
            'fk_cart_product_id',
            self::TABLE_NAME,
            'product_id',
            '{{%product}}',
            'id',
            'NO ACTION',
            'NO ACTION');
        $this->addForeignKey(
            'fk_cart_customer_id',
            self::TABLE_NAME,
            'customer_id',
            '{{%customer}}',
            'id',
            'NO ACTION',
            'NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('fk_cart_product_id', self::TABLE_NAME);
        $this->dropForeignKey('fk_cart_customer_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
