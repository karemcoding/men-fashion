<?php

use yii\db\Migration;

/**
 * Class m210611_040754_express_company
 */
class m210611_040754_express_company extends Migration
{
    const TABLE_NAME = '{{%express_company}}';

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'code' => $this->string(),
            'name' => $this->string(),
            'english_name' => $this->string(),
            'tel' => $this->string(),
            'address' => $this->string(1000),
            'website' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
