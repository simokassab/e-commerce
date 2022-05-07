<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use App\Models\CustomRole;
use PhpParser\Node\Expr\Cast\String_;

class RolesAndPermissionsService {

    public static function givePermissionToParentRoleAndChildren(array|Permission $permissions , CustomRole $roles) {
        return $roles->allChildren();
        $ids = collect($roles->allChildren()->children)
            ->map(function($item){
                return [$item->id, collect($item->children)->map('getIds')->all()];

            })  // call getIds function which will in turn do the same for all children
            ->flatten()      // flatten the resulting array
            ->sort()         // sort the resulting ids since they probably won't be in order
            ->values()       // only interested in the values, not the keys
            ->all();         // transform collection to array

        return $ids;

        $roles = $roles->allChildren()->children->map(function($item, $key) {

            $children_string = explode('.',RolesAndPermissionsService::generateRelationStringForRoleChildren($item->id));
            $roles_array = array();

            for($i = 0; $i < count($children_string); $i++){

                $children = CustomRole::with("children".str_repeat('.children',$i))->find($item->id);
                if( $children == null || !isset($children) ){
                    continue;
                }
                //there is data, we will enter the cuurent child to the array and do the same to ghe rest of the array uhnvtil we fisnhs all the arrays
                array_push($roles_array , $children);
            }

            return $roles_array;

        });


        return $roles;


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

    public static function generateRelationStringForRoleChildren(int | Role $role): Array
    {
        $allRoles = CustomRole::all();
        $roleChildren = [];
        foreach($allRoles as $currentRole){
            $parentId = ($currentRole->parent_id ?? 0);
            if(!isset($roleChildren[$parentId]))
                $roleChildren[$parentId] = [];
            $roleChildren[$parentId][] = $currentRole->id;
        }
        $roleId = (is_numeric($role) ? $role : $role->id);
        // $roleIds = self::getRoleChildren1($roleId, $roleChildren);
        $childRoles = [];
        self::drawRoleChildren($roleId, $roleChildren, $childRoles);
        // echo $roleId; print_r($roleChildren);
        // dd($roleIds);
        echo '<pre>';print_r($childRoles);
        return $childRoles;
    }

    public static function getRoleChildren1($parentRoleId, $allRoles){
        if(empty($allRoles[$parentRoleId]))
            return [];
        $childRoles = [];
        foreach($allRoles[$parentRoleId] as $childRoleId){
            $childRoles[] = $childRoleId;
            $childRoles = array_merge($childRoles, self::getRoleChildren1($childRoleId, $allRoles));
        }
        return $childRoles;
    }
    public static function drawRoleChildren($parentRoleId, $allRoles, &$childRoles){
        if(empty($allRoles[$parentRoleId]))
            return;
        foreach($allRoles[$parentRoleId] as $childRoleId){
            $childRoles[$childRoleId] = ['id' => $childRoleId, 'children' => []];
            self::drawRoleChildren($childRoleId, $allRoles, $childRoles[$childRoleId]['children']);
        }
    }

}


