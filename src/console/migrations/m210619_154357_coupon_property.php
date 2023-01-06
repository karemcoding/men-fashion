<?php

use yii\db\Migration;

/**
 * Class m210619_154357_coupon_attributes
 */
class m210619_154357_coupon_property extends Migration
{

    const TABLE_NAME = '{{%coupon_property}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'coupon_id' => $this->integer(),
            'key' => $this->string(255),
            'value' => $this->text(),
        ], $tableOptions);

        $this->createIndex('idx_unique_key', self::TABLE_NAME, ['coupon_id', 'key'], TRUE);

        $this->addForeignKey(
            'fk_coupon_property_map_id',
            self::TABLE_NAME,
            'coupon_id',
            '{{%coupon}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_coupon_property_map_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
