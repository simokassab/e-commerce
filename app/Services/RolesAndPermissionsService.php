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

}



