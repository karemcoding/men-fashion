<?php

use yii\db\Migration;

/**
 * Class m210422_160939_inventory_history
 */
class m210422_160939_inventory_history extends Migration
{
    const TABLE_NAME = '{{%inventory_history}}';

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'size'=>$this->string(),
            'warehouse_id' => $this->integer(),
            'inventory' => $this->double(),
            'quantity' => $this->double(),
            'type' => $this->tinyInteger(),
            'ref' => $this->string(),
            'description' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_inventory_history_product_id',
            self::TABLE_NAME,
            'product_id',
            '{{%product}}',
            'id',
            'NO ACTION',
            'NO ACTION');

        $this->addForeignKey(
            'fk_inventory_history_warehouse_id',
            self::TABLE_NAME,
            'warehouse_id',
            '{{%warehouse}}',
            'id',
            'NO ACTION',
            'NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('fk_inventory_history_product_id', self::TABLE_NAME);
        $this->dropForeignKey('fk_inventory_history_warehouse_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
