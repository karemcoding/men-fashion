<?php

use yii\db\Migration;

/**
 * Class m210622_044605_tb_email_content
 */
class m210622_044605_tb_email_content extends Migration
{
    /**
     * @return bool|void|null
     */
    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%email_content}}', [
            'id' => $this->primaryKey(),
            'template' => $this->string(255)->unique(),
            'subject' => $this->string(1000),
            'content' => $this->text(),
            'status' => $this->integer()->defaultValue('10'),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * @return bool|void|null
     */
    public function down()
    {
        $this->dropTable('{{%email_content}}');
    }
}
