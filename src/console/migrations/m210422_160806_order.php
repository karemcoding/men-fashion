<?php

use yii\db\Migration;

/**
 * Class m210422_160806_order
 */
class m210422_160806_order extends Migration
{
    const TABLE_NAME = '{{%order}}';

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'number' => $this->string()->unique(),
            'customer_id' => $this->integer()->notNull(),
            'delivery_address' => $this->string(),
            'subtotal' => $this->double(),
            'total' => $this->double(),
            'status' => $this->smallInteger()->defaultValue(10),
            'payment_status' => $this->tinyInteger(),
            'payment_method' => $this->tinyInteger(),
            'payment_ref_id' => $this->string(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_order_customer_id',
            self::TABLE_NAME,
            'customer_id',
            '{{%customer}}',
            'id',
            'NO ACTION',
            'NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('fk_order_customer_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
