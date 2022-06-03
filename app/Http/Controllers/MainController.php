<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use http\Client\Response;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;
use App\Exceptions\FileErrorException;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute\Attribute;
use Illuminate\Support\Facades\Cache;
use PDO;

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
    protected function successResponsePaginated($resource, $model, Array $relation=[] ,$pagination=null){
        return $resource::collection( $model::with($relation)->paginate( $pagination ?? config('defaults.default_pagination') ) );

    }

    protected function notFoundResponse(Array $data, $statusCode= 404){
        return notFoundError($data, $statusCode);
    }

    public function imageUpload($file,$folderpath){
        return uploadImage($file,$folderpath);
    }

    public function removeImage($folderpath){
        return removeImage($folderpath);
    }

    public function getSearchPaginated($resource,$model,$columnName,$value,$pagination=null){

        return  $resource::collection($model::where($columnName, 'LIKE', "%$value%")->paginate( $pagination ?? config('defaults.default_pagination')));
    }

    }


