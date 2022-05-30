<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
        return response()->json([
            'data' => $data
        ],$statusCode);
    }


    protected function errorResponse(Array $data, $statusCode= 500){
        return response()->json([
            'data' => $data
        ],$statusCode);
    }

    protected function notFoundResponse(Array $data, $statusCode= 404){
        return response()->json([
            'data' => $data
        ],$statusCode);
    }

    public function ImageUpload($file,$folderpath){
        return uploadImage($file,$folderpath);

        }

        public function test(){
            return config('defaults.default_icon_extentions');

        }

    }


