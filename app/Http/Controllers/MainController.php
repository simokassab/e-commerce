<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;


class MainController extends Controller
{
    protected $mapPermissions = [];

    public function __construct($defaultPermissionsFromChild = null)
    {
        $routeAction = basename(Route::currentRouteAction()); //we got the permission name

        if(isset($defaultPermissionsFromChild[$routeAction])){
            $routeAction = $defaultPermissionsFromChild[$routeAction];
        }
        // if(!auth()->user()->hasPermissionTo($routeAction)){
        //     $this->errorResponse(['message' => 'you are un authorized for this action'],401);
        // }

//        $this->defaultLocalize = config('app.locale');


//        $route_action = basename(Route::currentRouteAction());
//        if(isset($this->map_permissions[$route_action]))
//            $route_action = $this->map_permissions[$route_action];

    }

    protected function successResponse(Array $data, $statusCode= 200){
        return successResponse($data, $statusCode);
    }


    protected function errorResponse(Array $data, $statusCode= 500){
        return errorResponse($data, $statusCode);
    }

    protected function notFoundResponse(Array $data, $statusCode= 404){
        return notFoundError($data, $statusCode);
    }

}
