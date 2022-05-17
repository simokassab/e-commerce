<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



class MainController extends Controller
{
    protected $mapPermissions = [];

    public function __construct($defaultPermissionsFromChild = null)
    {

        $routeAction = basename(Route::currentRouteAction()); //we got the permission name

        if(isset($defaultPermissionsFromChild[$routeAction])){
            $routeAction = $defaultPermissionsFromChild[$routeAction];
        }
//        abort_if(!auth()->user()->hasPermissionTo($routeAction),401,'you are not authorized for this action');

//        $this->defaultLocalize = config('app.locale');


    }

    protected function successResponse($data, $statusCode= 200){
        return response()->json([
            'data' => $data
        ],$statusCode);
    }

    protected function errorResponse($data, $statusCode= 500){
        return response()->json([
            'data' => $data
        ],$statusCode);
    }

    protected function notFoundResponse($data, $statusCode= 404){
        return response()->json([
            'data' => $data
        ],$statusCode);
    }

}
