<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class MainController extends Controller
{
    protected $map_permissions = [];
    protected $SUCCESS_RESPONSE_CODE =200;

    public function __construct()
    {
        $route_action = basename(Route::currentRouteAction());
        if(isset($this->map_permissions[$route_action]))
            $route_action = $this->map_permissions[$route_action];
        if(authorize($route_action))
        parent::__construct();
    }

    protected function successResponse($data){
        response('data', $data, 200);
    }

}
