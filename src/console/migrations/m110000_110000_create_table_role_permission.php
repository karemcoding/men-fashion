<?php

use yii\db\Migration;

class m110000_110000_create_table_role_permission extends Migration
{

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%role_permission}}', [
            'role_id' => $this->integer()->notNull(),
            'permission_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PRIMARY_KEY', '{{%role_permission}}', ['role_id', 'permission_id']);

        $this->addForeignKey('fk_map_permission', '{{%role_permission}}',
            'permission_id',
            '{{%permission}}', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_map_role', '{{%role_permission}}', 'role_id',
            '{{%role}}', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('fk_map_permission', '{{%role_permission}}');
        $this->dropForeignKey('fk_map_role', '{{%role_permission}}');
        $this->dropTable('{{%role_permission}}');
    }
}
