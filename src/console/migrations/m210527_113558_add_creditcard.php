<?php

use yii\db\Migration;

/**
 * Class m210527_113558_add_creditcard
 */
class m210527_113558_add_creditcard extends Migration
{
    public function up()
    {
        $this->addColumn('{{%customer}}', 'credit_card_ref', $this->string()->after('address'));
    }

    public function down()
    {
        $this->dropColumn('{{%customer}}', 'credit_card_ref');
    }
}
