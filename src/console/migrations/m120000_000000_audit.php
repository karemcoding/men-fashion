<?php

use yii\base\NotSupportedException;
use yii\db\Migration;

/**
 * Class m120000_000000_audit
 */
class m120000_000000_audit extends Migration
{
    const TABLE_NAME = '{{%audit}}';

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
            'old' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT'),
            'new' => $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT'),
            'timestamp' => $this->integer(),
            'identity' => $this->string(),
            'identity_id' => $this->integer(),
            'identity_ip' => $this->string(),
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
