<?php

use yii\db\Migration;

/**
 * Class m210615_013655_add_group_into_customer
 */
class m210615_013655_add_group_into_customer extends Migration
{
    const TABLE_NAME = "{{%customer}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME,
            'group_id',
            $this->integer()->after('id'));

        $this->addForeignKey(
            'fk_customer_group_id',
            self::TABLE_NAME,
            'group_id',
            '{{%customer_group}}',
            'id',
            'SET NULL',
            'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_customer_group_id', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'group_id');
    }
}
