<?php

use yii\db\Migration;

class m110000_000000_create_table_role extends Migration
{

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%role}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue('10'),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->insert('{{%role}}', [
            'id' => 1,
            'name' => 'Administrator',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%role}}');
    }
}
