<?php

namespace App\Services\RolesAndPermissions;

use App\Models\RolesAndPermissions\CustomPermission;
use App\Models\RolesAndPermissions\CustomRole;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissionsServices {
    public static function getPermissionChildren(int | Permission $permission, $flatten= false) : Array
    {
        // got all the roles
        $allPermissions = CustomPermission::all(); //get all roles info

        // we passed the main role that we want to get its children along with the roles and there children
        $permissionChildren = self::generateChildrenForAllPermissions($allPermissions);

        //if the given data was numeric then take it as the roleId if not then take the id of the passed object
        $permissionId = (is_numeric($permission) ? $permission : $permission->id);


        return self::drawPermissionChildren($permissionId, $permissionChildren,!$flatten, $allPermissions);
    }


    private static function generateChildrenForAllPermissions($allPermissions):Array {
        $permissionChildren = [];
        foreach($allPermissions as $currentPermission){
            // get the parent ID if there is any if not set to 0, so the values of the first index `0` do not have any parents
            $parentId = ($currentPermission->parent_id ?? 0);

            // if the index was not set we just set it.
            if(!isset($permissionChildren[$parentId])){
                $permissionChildren[$parentId] = [];
            }
            // under the given index we add a new value the new child of that index
            $permissionChildren[$parentId][] = $currentPermission->id;
        }

        return $permissionChildren;
    }
    private static function drawPermissionChildren(Int $parentPermissionId, Array $allPermissionsID ,$isMultiLevel = false, $allPermissions): Array{ //with levels
        $childpermissions = array();
        if(empty($allPermissionsID[$parentPermissionId])){
            return [];
        }

        foreach($allPermissionsID[$parentPermissionId] as $permissionId){
            if($isMultiLevel){
                $childpermissions[$permissionId] = ['data' => $allPermissions->find($permissionId), 'children' => []];
                $childpermissions[$permissionId]['children'] = self::drawPermissionChildren($permissionId, $allPermissionsID, $isMultiLevel,$allPermissions);
            }
            else{
                $childpermissions[] = $permissionId;
                $childpermissions = array_merge($childpermissions, self::drawPermissionChildren($permissionId, $allPermissionsID, $isMultiLevel,$allPermissions));
            }
        }
        return $childpermissions;
    }


}
