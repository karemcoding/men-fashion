<?php

use yii\db\Migration;

/**
 * Class m210618_010501_table_order_fee
 */
class m210618_010501_table_order_fee extends Migration
{
    const TABLE_NAME = '{{%order_fee}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TABLE_NAME, [
            'order_id' => $this->integer(),
            'fee_id' => $this->integer(),
            'order_subtotal' => $this->float(),
            'fee_value' => $this->float(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_order_fee_fee_id',
            self::TABLE_NAME,
            'fee_id',
            '{{%fee}}',
            'id',
            'SET NULL', 'NO ACTION');

        $this->addForeignKey(
            'fk_order_fee_order_id',
            self::TABLE_NAME,
            'order_id',
            '{{%order}}',
            'id',
            'SET NULL', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_order_fee_fee_id', self::TABLE_NAME);
        $this->dropForeignKey('fk_order_fee_order_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
