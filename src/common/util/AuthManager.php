<?php

namespace common\util;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\rbac\CheckAccessInterface;

/**
 * Class AuthManager
 * @package common\util
 *
 * @property-read array|mixed $rolePermission
 */
class AuthManager extends Component implements CheckAccessInterface
{
    /**
     * @param $userId
     * @param $permissionName
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
}