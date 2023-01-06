<?php

use yii\db\Migration;

/**
 * Class m210702_023442_tb_returns
 */
class m210702_023442_tb_returns extends Migration
{
    const TABLE_NAME = '{{%order_returns}}';

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'note' => $this->string(1000),
            'status' => $this->smallInteger()->defaultValue(10),
            'remark' => $this->string(1000),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_order_returns_map_id',
            self::TABLE_NAME,
            'order_id',
            '{{%order}}',
            'id',
            'SET NULL',
            'NO ACTION');
    }

    /**
     * @return bool|void|null
     */
    public function down()
    {
        $this->dropForeignKey('fk_order_returns_map_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
