<?php

namespace common\models;

use common\util\AppHelper;
use common\util\Status;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\rbac\CheckAccessInterface;
use yii\web\IdentityInterface;

/**
 * @property int $id
 * @property string|null $username
 * @property int|null $role_id
 * @property string|null $email
 * @property string|null $tel
 * @property string|null $name
 * @property string|null $avatar
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $verification_token
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property-read RolePermission[] $rolePermissions
 * @property-read Permission[] $permissions
 * @property-write mixed $password
 * @property-read null|string|mixed $authKey
 * @property Role $role
 */
class User extends ActiveRecord implements IdentityInterface, CheckAccessInterface
{
    const STATUS_DELETED = Status::STATUS_DELETED;

    const STATUS_INACTIVE = Status::STATUS_INACTIVE;

    const STATUS_ACTIVE = Status::STATUS_ACTIVE;

    public static $alias = 'user';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @param int|string $id
     * @return array|User|ActiveRecord|IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return static::find()->alias('this')
            ->joinWith(['role role'])
            ->andWhere([
                'this.id' => $id,
                'this.status' => self::STATUS_ACTIVE,
                'role.status' => self::STATUS_ACTIVE])
            ->one();
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return User|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = NULL)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * @return array|int|mixed|string|null
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return mixed|string|null
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param int|string $userId
     * @param string $permissionName
     * @param array $params
     * @return bool
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        if (strpos('?', $permissionName) !== FALSE) {
            return TRUE;
        }

        if (Yii::$app->user->isGuest) {
            return FALSE;
        }

        /** @var self $user */
        $user = Yii::$app->user->identity;

        if ($user->isAdmin()) {
            return TRUE;
        }
        $permissions = ArrayHelper::getColumn($user->permissions, 'name');
        if (!empty($permissions) && ArrayHelper::isIn($permissionName, $permissions)) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param $token
     * @return User|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return NULL;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @param $token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return FALSE;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * @param $token
     * @return User|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @param $password
     *
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @throws Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @throws Exception
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = NULL;
    }

    /**
     * @return ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRolePermissions()
    {
        return $this->hasMany(RolePermission::class, ['role_id' => 'id'])
            ->via('role');
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
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role_id == Role::ADMIN_ROLE_ID;
    }

    /**
     * @return string
     */
    public function viewAvatar()
    {
        return AppHelper::webHostRoot() . $this->avatar;
    }

}
