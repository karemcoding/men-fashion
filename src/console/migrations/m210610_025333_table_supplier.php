<?php

use yii\db\Migration;

/**
 * Class m210610_025333_table_supplier
 */
class m210610_025333_table_supplier extends Migration
{
    const TABLE_NAME = '{{%product_supplier}}';

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
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'tel' => $this->string(50),
            'fax' => $this->string(50),
            'address' => $this->string(),
            'description' => $this->string(1000),
            'status' => $this->smallInteger()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
