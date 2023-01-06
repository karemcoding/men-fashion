<?php

use yii\db\Migration;

/**
 * Class m210425_140934_inventory
 */
class m210425_140934_inventory extends Migration
{
    const TABLE_NAME = '{{%inventory}}';

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            
            'size' => $this->string(),
            'product_id' => $this->integer(),
            'warehouse_id' => $this->integer(),
            'quantity' => $this->double(),
        ], $tableOptions);

        $this->addPrimaryKey('PRIMARY_KEY', self::TABLE_NAME, ['product_id', 'warehouse_id','size']);

        $this->addForeignKey(
            'fk_inventory_product_id',
            self::TABLE_NAME,
            'product_id',
            '{{%product}}',
            'id',
            'NO ACTION',
            'NO ACTION');

        $this->addForeignKey(
            'fk_inventory_warehouse_id',
            self::TABLE_NAME,
            'warehouse_id',
            '{{%warehouse}}',
            'id',
            'NO ACTION',
            'NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('fk_inventory_product_id', self::TABLE_NAME);
        $this->dropForeignKey('fk_inventory_warehouse_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
