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
    public static function getPermissionChildren(int | Permission $permission,$permissionsOfRole = [], $flatten= false) : Array
    {
        // got all the roles
        $allPermissions = CustomPermission::all(); //get all roles info

        // we passed the main role that we want to get its children along with the roles and there children
        $permissionChildren = self::generateChildrenForAllPermissions($allPermissions);
        //if the given data was numeric then take it as the roleId if not then take the id of the passed object
        $permissionId = (is_numeric($permission) ? $permission : $permission->id);

        return self::drawPermissionChildren($permissionId, $permissionChildren,!$flatten, $allPermissions,$permissionsOfRole);
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
    public static function drawPermissionChildren(Int $parentPermissionId, Array $allPermissionsID , $isMultiLevel = false, $allPermissions,$permissionsOfRole=[]): Array{ //with levels
        $childpermissions = array();
        $permissionsOfRoleIds= array_column($permissionsOfRole, 'id');
        if(empty($allPermissionsID[$parentPermissionId])){
            return [];
        }
        foreach($allPermissionsID[$parentPermissionId] as $permissionId){
            $permissionId =  is_numeric($permissionId)? ($permissionId) : $permissionId->id;
            if($isMultiLevel){
                $childpermissions[$permissionId] = (object)['id' => $allPermissions->find($permissionId)->id,'label' => $allPermissions->find($permissionId)->name ,'id' => $allPermissions->find($permissionId)->id,'checked' => in_array($allPermissions->find($permissionId)->id,  $permissionsOfRoleIds), 'nodes' => []];
                $childpermissions[$permissionId]->nodes = self::drawPermissionChildren($permissionId, $allPermissionsID, $isMultiLevel,$allPermissions);
            }
            else{
                $childpermissions[] = $permissionId;
                $childpermissions = array_merge($childpermissions, self::drawPermissionChildren($permissionId, $allPermissionsID, $isMultiLevel,$allPermissions,$permissionsOfRole));
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

    public static function getAllPermissionsNested(Array $permissions,Array $permissionsOfRole=[]){
        $permissionsOfRoleIds= array_column($permissionsOfRole, 'id');
        $lastResult = [];
        $rootPermissions = self::getRootPermissions($permissions);
        foreach ($rootPermissions as $rootPermission){
            $result = (object)[];
            $result->id = $rootPermission->id;
            $result->label = $rootPermission->name;
            $result->checked = in_array($rootPermission->id ?? 0, $permissionsOfRoleIds);
            $result->nodes = self::getPermissionChildren($rootPermission,$permissionsOfRole);
            $lastResult[] = $result;
        }
        return $lastResult;

    }

//    public static function markRolesPermissionAsChecked(Array $permissionsOfRole,Array &$permissions){
//
//
//        foreach ($permissions as $key => $permission){
//            //here we will check if the permission exists
//            // if exists it will convert the checked from false to true
//            if(){
//                $permission->checked = true;
//            }
//            if(array_key_exists('nodes' ,(array)$permission) && sizeof($permission->nodes )!= 0){
//                self::markRolesPermissionAsChecked($permissionsOfRole,$permissions);
//                if($key % 2 == 0)
//                    dd('d');
//            }
//            dd($permissions);
//        }
//
//
//    }


}
