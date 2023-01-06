<?php

use yii\db\Migration;

class m110000_150000_create_table_setting extends Migration
{

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%setting}}', [
            'key' => $this->string(255)->unique(),
            'value' => $this->text()
        ], $tableOptions);

        $this->addPrimaryKey('PRIMARY_KEY', '{{%setting}}', 'key');
    }

    public function down()
    {
        $this->dropTable('{{%setting}}');
    }
}
