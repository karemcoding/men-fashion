<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 * @property int $parent_id
 * @property int $synced
 *
 * @property RolePermission[] $rolePermissions
 * @property-read Permission $parent
 * @property-read Permission[] $children
 * @property Role[] $roles
 */
class Permission extends ActiveRecord
{
    public static $alias = 'permission';

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%permission}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'synced', 'parent_id'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['parent_id'], 'exist',
                'skipOnError' => TRUE,
                'targetClass' => Permission::class,
                'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Mã'),
            'name' => Yii::t('common', 'Tên'),
            'description' => Yii::t('common', 'Mô tả'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'synced' => Yii::t('common', 'Synced'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getRolePermissions()
    {
        return $this->hasMany(RolePermission::class, ['permission_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::class, ['id' => 'role_id'])
            ->via('rolePermissions');
    }

    /**
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Permission::class, ['id' => 'parent_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Permission::class, ['parent_id' => 'id']);
    }
}
