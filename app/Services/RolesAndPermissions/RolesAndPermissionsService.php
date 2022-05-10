<?php

namespace App\Services\RolesAndPermissions;

use App\Models\RolesAndPermissions\CustomRole;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsService {

    public static function givePermissionToParentRoleAndChildren(array|Permission $permissions , CustomRole $roles) {
        return $roles->allChildren();

    }

    public static function getRoleChildren(int | Role $role, $flatten= false) : Array
    {
        // got all the roles
        $allRoles = CustomRole::all(); //get all roles info

        // we passed the main role that we want to get its children along with the roles and there children
        $roleChildren = self::generateChildrenForAllRoles($allRoles);

        //if the given data was numeric then take it as the roleId if not then take the id of the passed object
        $roleId = (is_numeric($role) ? $role : $role->id);


        return self::drawRoleChildren($roleId, $roleChildren,!$flatten, $allRoles);
    }

    private static function generateChildrenForAllRoles($allRoles):Array {
        $roleChildren = [];
        foreach($allRoles as $currentRole){
            // get the parent ID if there is any if not set to 0, so the values of the first index `0` do not have any parents
            $parentId = ($currentRole->parent_id ?? 0);

            // if the index was not set we just set it.
            if(!isset($roleChildren[$parentId])){
                $roleChildren[$parentId] = [];
            }
            // under the given index we add a new value the new child of that index
            $roleChildren[$parentId][] = $currentRole->id;
        }

        return $roleChildren;

    }

    private static function drawRoleChildren(Int $parentRoleId, Array $allRolesID ,$isMultiLevel = false, $allRoles): Array{ //with levels
        if(empty($allRolesID[$parentRoleId])){
            return [];
        }

        foreach($allRolesID[$parentRoleId] as $childRoleId){
            if($isMultiLevel){
                $childRoles[$childRoleId] = ['data' => $allRoles->find($childRoleId), 'children' => []];
                $childRoles[$childRoleId]['children'] = self::drawRoleChildren($childRoleId, $allRolesID, $isMultiLevel,$allRoles);
            }
            else{
                $childRoles[] = $childRoleId;
                $childRoles = array_merge($childRoles, self::drawRoleChildren($childRoleId, $allRolesID, $isMultiLevel,$allRoles));
            }
        }
        return $childRoles;
    }


    // public static function generateRelationStringForRoleChildren(int | Role $role): String
    // {
    //     $old_count = 0;
    //     $relations = "children";
    //     $query = CustomRole::find($role);
    //     $current_count = collect($query->load($relations))->flatten()->count();


    //     while($current_count > $old_count){
    //         $old_count = collect($query->load($relations))->flatten()->count();
    //         $relations .= ".children";
    //         $current_count = collect($query->load($relations))->flatten()->count();
    //     }

    //     return $relations;
    // }

}


