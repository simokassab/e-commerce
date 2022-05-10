<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use App\Services\RolesAndPermissionsService;

class TestController extends Controller
{
    use HasRoles;

    public function test(){

        $permissionsToPermit = Permission::findMany([1,2,3]);
    }
}
