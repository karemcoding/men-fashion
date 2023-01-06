<?php

use yii\db\Migration;

/**
 * Class m210610_075303_add_cols_brand_supplier
 */
class m210610_075303_add_cols_brand_supplier extends Migration
{
    const TABLE_NAME = "{{%product}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME,
            'brand_id',
            $this->integer()->after('inventory'));
        $this->addColumn(self::TABLE_NAME,
            'supplier_id',
            $this->integer()->after('inventory'));
        $this->addForeignKey(
            'fk_product_brand_id',
            self::TABLE_NAME,
            'brand_id',
            '{{%product_brand}}',
            'id',
            'SET NULL',
            'NO ACTION');

        $this->addForeignKey(
            'fk_product_supplier_id',
            self::TABLE_NAME,
            'supplier_id',
            '{{%product_supplier}}',
            'id',
            'SET NULL',
            'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_product_brand_id', self::TABLE_NAME);
        $this->dropForeignKey('fk_product_supplier_id', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'brand_id');
        $this->dropColumn(self::TABLE_NAME, 'supplier_id');
    }
}
