<?php

namespace App\Services\RolesAndPermissions;

use App\Models\RolesAndPermissions\CustomPermission;
use App\Models\RolesAndPermissions\CustomRole;
use PhpParser\Node\Expr\Cast\Object_;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use function PHPUnit\Framework\isNull;

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


    public static function generateChildrenForAllPermissions($allPermissions):Array {
        $permissionChildren = [];
        foreach($allPermissions as $currentPermission){
            // get the parent ID if there is any if not set to 0, so the values of the first index `0` do not have any parents
            $parentId = ($currentPermission->parent_id ?? 0);

            // if the index was not set we just set it.
            if(!isset($permissionChildren[$parentId])){
                $permissionChildren[$parentId] = [];
            }
            // under the given index we add a new value the new child of that index
            $permissionChildren[$parentId][] = CustomPermission::find($currentPermission->id);
        }

        return $permissionChildren;
    }

    /**
     * @param Int $parentPermissionId
     * @param array $allPermissionsID
     * @param $isMultiLevel
     * @param $allPermissions
     * @return Array
     */
    public static function drawPermissionChildren(Int $parentPermissionId, Array $allPermissionsID , $isMultiLevel = false, $allPermissions): Array{ //with levels
        $childpermissions = array();
        if(empty($allPermissionsID[$parentPermissionId])){
            return [];
        }
        foreach($allPermissionsID[$parentPermissionId] as $permissionId){
            $permissionId =  is_numeric($permissionId)? ($permissionId) : $permissionId->id;
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

    public static function getRootPermissions(Array $permissions){
        $arrayOfParents = [];
        foreach($permissions as $permission){
            if(!is_null($permission->parent) || $permission->parent_id ) {
                continue;
            }
            if(!in_array($permission,$arrayOfParents)){
                $arrayOfParents[] = $permission;
            }

        }
        return ($arrayOfParents);
    }

    public static function getAllPermissionsNested(Array $permissions){

        $rootPermissions = self::getRootPermissions($permissions);
        $result = (object)[];
        foreach ($rootPermissions as $rootPermission){
            $result->label = $rootPermission->name;
            $result->nodes = self::getPermissionChildren($rootPermission);
            $result->id= $rootPermission->id;
            $result->checked = false;
            dd($result);
        }
        return $result;

    }


}
