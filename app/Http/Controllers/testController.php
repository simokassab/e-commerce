<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User\User;

class TestController extends Controller
{
    use HasRoles;

    public function test(){

    }

    public function getToken(){
        return User::first()->createToken('developer-access');
    }
}
