<?php

use yii\db\Migration;

/**
 * Class m210526_020915_update
 */
class m210526_020915_update extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'payment_payer_ref_id', $this->string()->after('payment_ref_id'));
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'payment_payer_ref_id');
    }
}
