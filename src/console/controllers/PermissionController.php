<?php

namespace console\controllers;

use common\models\Permission;
use common\models\RolePermission;
use Symfony\Component\Yaml\Yaml;
use Yii;
use yii\console\Controller;

/**
 * Class PermissionsController
 *
 * @package console\controllers
 */
class PermissionController extends Controller
{

    /**
     * Update permissions
     */
    public function actionIndex()
    {
        $permissions = $this->_findPermission();
        $permission_data = [];

        foreach ($permissions as $parent_name => $item) {
            if (!$parent_permission = Permission::findOne(['name' => $parent_name])) {
                $parent_permission = new Permission(['name' => $parent_name]);
            }
            $parent_permission->description = $item['description'] ?? $parent_name;
            $parent_permission->synced = 1;
            $parent_permission->parent_id = NULL;
            $parent_permission->save();

            if (!empty($item['child'])) {
                foreach ($item['child'] as $child_name => $child_desc) {
                    $child_name = implode(" ",
                        array_unique([$parent_name, $child_name]));

                    if (!$child_permission = Permission::findOne(['name' => $child_name])) {
                        $child_permission = new Permission([
                            'name' => $child_name,
                            'parent_id' => $parent_permission->id
                        ]);
                    }
                    $child_permission->description = $child_desc ?? $child_name;
                    $child_permission->synced = 1;
                    if ($child_permission->save()) {
                        $permission_data[] = $child_permission;
                    }
                }
            }
        }

        if ($permission_data) {
            $old_permissions = Permission::find()
                ->andWhere(['synced' => 0])
                ->indexBy('id')
                ->asArray()
                ->all();

            if (!empty($old_permissions)) {
                $old_permissions = array_keys($old_permissions);
                RolePermission::deleteAll(['permission_id' => $old_permissions]);
                Permission::deleteAll(['id' => $old_permissions]);
            }

            Permission::updateAll(['synced' => 0]);

            echo Yii::t('common',
                    "Total {n,plural,=1{1 permission is} other{# permissions are}} updated",
                    ['n' => count($permission_data)]) . "\n";
        } else {
            echo "No any new permissions are found\n";
        }
    }

    /**
     * @return array|mixed
     */
    private function _findPermission()
    {
        $permissions = [];
        $app_path = Yii::getAlias("@console/src/permissions.yml");
        if (file_exists($app_path)) {
            $permissions = Yaml::parse(file_get_contents($app_path));
        }

        return $permissions;
    }
}
