<?php

use yii\db\Migration;

/**
 * Class m210630_165646_product_discount
 */
class m210630_165646_product_discount extends Migration
{
    const TABLE_NAME = '{{%product_discount}}';

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'discount_id' => $this->integer(),
            'origin_price' => $this->float(),
            'discount_price' => $this->float(),
            'status' => $this->smallInteger()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_discount_map_product_id',
            self::TABLE_NAME,
            'product_id',
            '{{%product}}',
            'id',
            'SET NULL', 'NO ACTION');

        $this->addForeignKey(
            'fk_product_map_discount_id',
            self::TABLE_NAME,
            'discount_id',
            '{{%discount}}',
            'id',
            'SET NULL', 'NO ACTION');

        $this->createIndex('idx_product_discount',
            self::TABLE_NAME,
            ['product_id', 'discount_id'],
            TRUE);
    }

    /**
     * @return bool|void|null
     */
    public function down()
    {
        $this->dropForeignKey('fk_product_map_discount_id', self::TABLE_NAME);
        $this->dropForeignKey('fk_discount_map_product_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
