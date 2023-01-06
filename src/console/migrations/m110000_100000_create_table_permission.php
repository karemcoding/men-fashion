<?php

use yii\db\Migration;

class m110000_100000_create_table_permission extends Migration
{

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%permission}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'parent_id' => $this->integer()->null(),
            'synced' => $this->tinyInteger()->notNull()->defaultValue('0'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_permission_parent_id',
            '{{%permission}}',
            'parent_id',
            '{{%permission}}',
            'id',
            'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_permission_parent_id', '{{%permission}}');
        $this->dropTable('{{%permission}}');
    }
}
