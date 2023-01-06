<?php

use yii\db\Migration;

/**
 * Class m210629_141511_tb_discount
 */
class m210629_141511_tb_discount extends Migration
{
    const TABLE_NAME = '{{%discount}}';

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
            'description' => $this->string(),
            'default_value' => $this->float(),
            'from' => $this->integer(),
            'to' => $this->integer(),
            'type' => $this->smallInteger(),
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
