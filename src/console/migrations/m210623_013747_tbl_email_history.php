<?php

use yii\db\Migration;

/**
 * Class m210623_013747_tbl_email_history
 */
class m210623_013747_tbl_email_history extends Migration
{
    const TABLE_NAME = '{{%email_history}}';

    /**
     * @return bool|void|null
     */
    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'receiver' => $this->text(),
            'subject' => $this->string(1000),
            'content' => $this->text(),
            'sent_at' => $this->integer(),
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
        $this->dropTable(self::TABLE_NAME);
    }
}
