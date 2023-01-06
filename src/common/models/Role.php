<?php

namespace common\models;

use common\behaviors\status\StatusBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property RolePermission[] $rolePermissions
 * @property User[] $users
 * @property Permission[] $permissions
 * @property User $author
 * @property User $updater
 */
class Role extends ActiveRecord
{
    const ADMIN_ROLE_ID = 1;

    public static $alias = 'role';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_by',
                'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'validateDuplicate']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã'),
            'name' => Yii::t('common', 'Nhóm nhân viên'),
            'status' => Yii::t('common', 'Trạng thái'),
            'is_primary' => Yii::t('common', 'Is Primary'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'created_at' => Yii::t('common', 'Người cập nhật'),
            'updated_by' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['status'] = StatusBehavior::class;

        return $behaviors;
    }

    /**
     * @return ActiveQuery
     */
    public function getRolePermissions()
    {
        return $this->hasMany(RolePermission::class, ['role_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(Permission::class, ['id' => 'permission_id'])
            ->via('rolePermissions');
    }

    /**
     * @return ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['role_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * @param $permission_name
     *
     * @return bool
     */
    public function hasPermission($permission_name)
    {
        $permissions = ArrayHelper::getColumn($this->permissions, 'name');
        if (in_array($permission_name, $permissions)) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->id === self::ADMIN_ROLE_ID;
    }

    /**
     * @return array
     */
    public static function buildSelect2()
    {
        $roles = self::find()->notDeleted()->all();
        return ArrayHelper::map($roles, 'id', 'name');
    }
}
