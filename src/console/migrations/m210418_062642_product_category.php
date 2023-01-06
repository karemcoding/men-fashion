<?php

use yii\db\Migration;

/**
 * Class m210418_062642_product_category
 */
class m210418_062642_product_category extends Migration
{
    const TABLE_NAME = '{{%product_category}}';

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->text(),
            'thumbnail' => $this->string(1000),
            'tree' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
