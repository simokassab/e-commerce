<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use App\Models\CustomRole;
use Spatie\Permission\Models\Permission;


class TestController extends Controller
{
    use HasRoles;

    public function test(){
        return(CustomRole::has('children.children')->get());
    }
}
