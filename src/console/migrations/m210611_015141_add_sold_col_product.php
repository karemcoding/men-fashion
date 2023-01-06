<?php

use yii\db\Migration;

/**
 * Class m210611_015141_add_sold_col_product
 */
class m210611_015141_add_sold_col_product extends Migration
{
    const TABLE_NAME = "{{%product}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME,
            'sold',
            $this->float()->after('inventory')->defaultValue(0));
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'sold');
    }
}
