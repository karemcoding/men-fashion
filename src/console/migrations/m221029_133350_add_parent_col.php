<?php

use yii\db\Migration;

/**
 * Class m221029_133350_add_related_col
 */
class m221029_133350_add_parent_col extends Migration
{
    const TABLE_NAME = '{{%product}}';

    public function up()
    {
        $this->addColumn(self::TABLE_NAME, 'parent_id', $this->integer()->after('id'));
    }

    public function down()
    {
        $this->dropColumn(self::TABLE_NAME, 'parent_id');
    }
}
