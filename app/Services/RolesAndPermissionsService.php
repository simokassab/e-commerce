<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use App\Models\CustomRole;

class RolesAndPermissionsService {

    public static function givePermissionToParentRoleAndChildren(array|object $permissions , CustomRole $role) {
        $roles = collect(array_merge(array($role), $role->allChildren()->children->toArray()));

        $modified_roles_array = $roles->map(function($item, $key) {

            dd ($item->children);

        });


        return $modified_roles_array;


    }

}


