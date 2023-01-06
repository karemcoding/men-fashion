<?php

use yii\db\Migration;

/**
 * Class m221029_141736_add_size_column
 */
class m221029_141736_add_size_column extends Migration
{
    const TABLE_NAME = '{{%product}}';

    public function up()
    {
        $this->addColumn(self::TABLE_NAME, 'size', $this->string());
    }

    public function down()
    {
        $this->dropColumn(self::TABLE_NAME, 'size');
    }
}
