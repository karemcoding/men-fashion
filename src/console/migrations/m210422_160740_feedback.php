<?php

use yii\db\Migration;

/**
 * Class m210422_160740_feedback
 */
class m210422_160740_feedback extends Migration
{
    const TABLE_NAME = '{{%feedback}}';

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'feedback' => $this->string(1000),
            'score' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_feedback_product_id',
            self::TABLE_NAME,
            'product_id',
            '{{%product}}',
            'id',
            'NO ACTION',
            'NO ACTION');
        $this->addForeignKey(
            'fk_feedback_customer_id',
            self::TABLE_NAME,
            'customer_id',
            '{{%customer}}',
            'id',
            'NO ACTION',
            'NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('fk_feedback_product_id', self::TABLE_NAME);
        $this->dropForeignKey('fk_feedback_customer_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
