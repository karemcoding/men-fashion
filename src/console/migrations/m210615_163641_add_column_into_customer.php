<?php

use yii\db\Migration;

/**
 * Class m210615_163641_add_column_into_customer
 */
class m210615_163641_add_column_into_customer extends Migration
{
    const TABLE_NAME = "{{%customer}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'avatar', $this->string()->after('address'));
        $this->addColumn(self::TABLE_NAME, 'created_by', $this->integer());
        $this->addColumn(self::TABLE_NAME, 'updated_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'avatar');
        $this->dropColumn(self::TABLE_NAME, 'created_by');
        $this->dropColumn(self::TABLE_NAME, 'updated_by');
    }
}
