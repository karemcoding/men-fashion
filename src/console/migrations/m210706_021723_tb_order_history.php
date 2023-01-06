<?php

use yii\base\NotSupportedException;
use yii\db\Migration;

/**
 * Class m210706_021723_tb_order_history
 */
class m210706_021723_tb_order_history extends Migration
{
    const TABLE_NAME = '{{%order_history}}';

    /**
     * @return bool|void
     * @throws NotSupportedException
     */
    public function safeUp()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'operation' => $this->string(),
            'model' => $this->string(),
            'record_pk' => $this->string(),
            'attribute' => $this->string(),
            'old' => $this->string(),
            'new' => $this->string(),
            'timestamp' => $this->integer(),
            'identity' => $this->string(),
            'identity_id' => $this->integer(),
            'identity_ip' => $this->string(),
        ], $tableOptions);
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
