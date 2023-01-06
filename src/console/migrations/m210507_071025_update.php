<?php

use yii\db\Migration;

/**
 * Class m210507_071025_update
 */
class m210507_071025_update extends Migration
{
    public function up()
    {
        $this->addColumn(
            '{{%product}}',
            'hot',
            $this->tinyInteger()->after('thumbnail'));
    }
}
