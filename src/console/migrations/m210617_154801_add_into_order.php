<?php

use yii\db\Migration;

/**
 * Class m210617_154801_add_into_order
 */
class m210617_154801_add_into_order extends Migration
{
    const TABLE_NAME = '{{%order}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'remark',
            $this->string()->after('payment_payer_ref_id'));
        $this->addColumn(self::TABLE_NAME, 'receiver',
            $this->string()->after('customer_id'));
        $this->addColumn(self::TABLE_NAME, 'receiver_tel',
            $this->string()->after('receiver'));
        $this->addColumn(self::TABLE_NAME, 'delivery_date',
            $this->integer()->after('delivery_address'));
        $this->addColumn(self::TABLE_NAME, 'express_company_id',
            $this->integer()->after('delivery_date'));
        $this->addForeignKey(
            'fk_order_express_company_id',
            self::TABLE_NAME,
            'express_company_id',
            '{{%express_company}}',
            'id',
            'SET NULL', 'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_order_express_company_id', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'remark');
        $this->dropColumn(self::TABLE_NAME, 'receiver');
        $this->dropColumn(self::TABLE_NAME, 'receiver_tel');
        $this->dropColumn(self::TABLE_NAME, 'delivery_date');
        $this->dropColumn(self::TABLE_NAME, 'express_company_id');
    }
}
