<?php

use yii\db\Migration;

/**
 * Class m210609_111804_add_root_category
 */
class m210609_111804_add_root_category extends Migration
{
    const TABLE_NAME = '{{%product_category}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(self::TABLE_NAME, [
            'id' => 1,
            'name' => 'ROOT',
            'description' => 'ROOT CATEGORY',
            'tree' => 1,
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
            'status' => 10,
            'created_by' => 1,
            'updated_by' => 1,
            'updated_at' => time(),
            'created_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
