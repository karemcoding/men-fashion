<?php

use yii\db\Migration;

/**
 * Class m210620_030716_table_orther_coupon
 */
class m210620_030716_table_orther_coupon extends Migration
{
    const TABLE_NAME = '{{%order_coupon}}';

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
            'coupon_id' => $this->integer(),
            'coupon_value' => $this->float(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_order_coupon_coupon_id',
            self::TABLE_NAME,
            'coupon_id',
            '{{%coupon}}',
            'id',
            'SET NULL', 'NO ACTION');

        $this->addForeignKey(
            'fk_order_coupon_order_id',
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
        $this->dropForeignKey('fk_order_coupon_coupon_id', self::TABLE_NAME);
        $this->dropForeignKey('fk_order_coupon_order_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
