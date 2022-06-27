<?php

namespace App\Http\Controllers;
use App\Models\Currency\Currency;
use App\Models\RolesAndPermissions\CustomRole;
use Illuminate\Http\Request;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\MainController;
use App\Exceptions\FileErrorException;
use App\Models\RolesAndPermissions\CustomPermission;

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
//         $routes = app('router')->getRoutes();
// //        dd($routes[0]);
// //


//         Artisan::call('route:list');


// //        $allRoutes = Route::getRoutes()->get();
// //        $routes = [];
// //        foreach($allRoutes as $route) {
// //        $routes[] = $route->getPath();
// //        }

//         dd($routes);


    }
}
