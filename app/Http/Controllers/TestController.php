<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User\User;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;

class TestController extends Controller
{
    use HasRoles;

    public function getToken(){
        return User::first()->createToken('my-token');
    }
}
