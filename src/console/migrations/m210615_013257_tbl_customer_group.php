<?php

use yii\db\Migration;

/**
 * Class m210615_013257_tbl_customer_group
 */
class m210615_013257_tbl_customer_group extends Migration
{
    const TABLE_NAME = "{{%customer_group}}";

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
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->string(500),
            'status' => $this->smallInteger()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->insert(self::TABLE_NAME, [
            'id' => 1,
            'name' => 'Default',
            'description' => "Default member group",
            'status' => 10,
            'created_by' => 1,
            'updated_by' => 1,
            'updated_at' => time(),
            'created_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
