<?php

use yii\db\Migration;

/**
 * Class m210518_044852_update_customer
 */
class m210518_044852_update_customer extends Migration
{

    public function up()
    {
        $this->addColumn('{{%customer}}',
            'name',
            $this->string(50)
                ->null()->after('phone'));
        $this->addColumn('{{%customer}}',
            'address',
            $this->string()
                ->null()->after('name'));

    }

    public function down()
    {
        $this->dropColumn('{{%customer}}', 'name');
        $this->dropColumn('{{%customer}}', 'address');
    }
}
