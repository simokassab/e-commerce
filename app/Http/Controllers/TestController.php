<?php

namespace App\Http\Controllers;

use App\Models\RolesAndPermissions\CustomRole;
use Illuminate\Http\Request;
use App\Models\User\User;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\MainController;

class TestController extends MainController
{
    use HasRoles;

    public function __construct()
    {
        $this->map_permissions = [

        ];

        parent::__construct($this->map_permissions);
    }

    public function getToken(){
        return User::first()->createToken('my-token');
    }

    public function test(){
        return Permission::findOrFail(50000);
    }
}
