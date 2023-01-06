<?php

use yii\db\Migration;

/**
 * Class m210422_160557_product
 */
class m210422_160557_product extends Migration
{
    const TABLE_NAME = '{{%product}}';

    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'sku' => $this->string(),
            'category_id' => $this->integer(),
            'name' => $this->string(),
            'slug' => $this->string(),
            'price' => $this->double(),
            'description' => $this->text(),
            'thumbnail' => $this->text(),
            'gallery' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(10),
            'inventory' => $this->float()->defaultValue(0),
            'unit' => $this->string()->defaultValue('PCE'),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_product_category_id',
            self::TABLE_NAME,
            'category_id',
            '{{%product_category}}',
            'id',
            'SET NULL',
            'NO ACTION');
    }

    public function down()
    {
        $this->dropForeignKey('fk_product_category_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
