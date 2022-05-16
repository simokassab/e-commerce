<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class MainController extends Controller
{
    protected $defaultLocalize;
    protected $map_permissions = [];
    protected $SUCCESS_RESPONSE_CODE =200;

    public function __construct()
    {
        $route_action = basename(Route::currentRouteAction());
        if(isset($this->map_permissions[$route_action]))
            $route_action = $this->map_permissions[$route_action];
    //        if(authorize($route_action)){
    //
    //        }

//        $this->defaultLocalize = config('app.locale');


//        parent::__construct();
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
