<?php

use yii\db\Migration;

/**
 * Class m210611_010817_add_reason_col_inventory_history
 */
class m210611_010817_add_reason_col_inventory_history extends Migration
{
    const TABLE_NAME = "{{%inventory_history}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME,
            'reason_id',
            $this->integer()->after('ref'));
        $this->addForeignKey(
            'fk_inventory_history_reason_id',
            self::TABLE_NAME,
            'reason_id',
            '{{%inventory_reason}}',
            'id',
            'SET NULL',
            'NO ACTION');
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_inventory_history_reason_id', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'reason_id');
    }
}
