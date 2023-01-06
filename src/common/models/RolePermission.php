<?php

namespace common\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%role_permission}}".
 *
 * @property int $role_id
 * @property int $permission_id
 *
 * @property Permission $permission
 * @property Role $role
 */
class RolePermission extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role_permission}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'permission_id'], 'required'],
            [['role_id', 'permission_id'], 'integer'],
            [['permission_id'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => Permission::class,
                'targetAttribute' => ['permission_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => TRUE,
                'targetClass' => Role::class,
                'targetAttribute' => ['role_id' => 'id']],
            [['role_id', 'permission_id'], 'unique', 'skipOnError' => TRUE,
                'targetClass' => self::class,
                'targetAttribute' => ['role_id', 'permission_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_id' => Yii::t('common', 'Role ID'),
            'permission_id' => Yii::t('common', 'Permission ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermission()
    {
        return $this->hasOne(Permission::class, ['id' => 'permission_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     * @param array $data
     *
     * @return bool|int
     * @throws InvalidConfigException
     * @throws Exception
     */
    public static function updatePermission($data = [])
    {
        $attributes = self::getTableSchema()->getColumnNames();

        if (self::validateMultiple($data, $attributes)) {
            $role_id = ArrayHelper::getColumn($data, 'role_id');
            self::deleteAll(['role_id' => $role_id]);

            return Yii::$app->db->createCommand()
                ->batchInsert(self::tableName(), $attributes, $data)->execute();
        }

        return FALSE;
    }
}
