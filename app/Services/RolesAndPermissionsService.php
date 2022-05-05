<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use App\Models\CustomRole;

class RolesAndPermissionsService {

    public function givePermissionToParentRoleAndChildren(array|object $permissions , CustomRole $role) {
        $role->children;
    }

    public static function getAllChildren(int $id){

        return CustomRole::with( RolesAndPermissionsService::generateRelation($id) )->find($id);

    }

    private static function generateRelation(int $id){
        $old_count = 0;
        $relations = "children";
        $current_count = collect(CustomRole::with($relations)->find($id))->flatten()->count();

        while($current_count > $old_count){
            $old_count = collect(CustomRole::with($relations)->find($id))->flatten()->count();
            $relations .= ".children";
            $current_count = collect(CustomRole::with($relations)->find($id))->flatten()->count();
        }

        return $relations;
    }

}



