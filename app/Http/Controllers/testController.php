<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use App\Models\RolesAndPermissions\CustomRole;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User\User;

class TestController extends Controller
{
    use HasRoles;

    public function test(){
        return CustomRole::find(16);
    }

    public function getToken(){
        return User::first()->createToken('developer-access');
    }
}
