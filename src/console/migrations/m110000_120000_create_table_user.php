<?php

use yii\db\Migration;

class m110000_120000_create_table_user extends Migration
{
    const TABLE_NAME = '{{%user}}';

    /**
     * @return bool|void|null
     * @throws \yii\base\Exception
     */
    public function up()
    {
        $tableOptions = NULL;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'username' => $this->string(),
            'role_id' => $this->integer(),
            'email' => $this->string(),
            'tel' => $this->string(30),
            'name' => $this->string(),
            'avatar' => $this->text(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'verification_token' => $this->string()->unique(),
            'status' => $this->smallInteger()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_user_role_id',
            self::TABLE_NAME,
            'role_id',
            '{{%role}}',
            'id',
            'SET NULL', 'NO ACTION');

        $this->insert('{{%user}}', [
            'id' => 1,
            'username' => 'admin',
            'role_id' => 1,
            'email' => 'ltanh1194@gmail.com',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'created_by' => 1,
            'updated_by' => 1,
            'updated_at' => time(),
            'created_at' => time(),
        ]);
    }

    public function down()
    {
        $this->dropForeignKey('fk_user_role_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}
