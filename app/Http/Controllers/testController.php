<?php

namespace App\Http\Controllers;

use App\Services\RolesAndPermissionsService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class TestController extends Controller
{
    use HasRoles;

    public function test(){

        $permissionsToPermit = Permission::findMany([1,2,3]);
    }
}
