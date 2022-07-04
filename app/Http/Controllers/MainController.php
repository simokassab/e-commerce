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
use App\Http\Resources\CategoryResource;
use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeValue;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;

class MainController extends Controller
{
//    protected $mapPermissions = [];

    public function __construct(/*$defaultPermissionsFromChild = null*/)
    {
        $routeAction = basename(Route::currentRouteAction()); //we got the permission name

//        if (isset($defaultPermissionsFromChild[$routeAction])) {
//            $routeAction = $defaultPermissionsFromChild[$routeAction];
//        }
         if(!auth()->user()->hasPermissionTo($routeAction)){
             $this->errorResponse(['message' => 'you are un authorized for this action'],401);
         }

//        $this->defaultLocalize = config('app.locale');


//        $route_action = basename(Route::currentRouteAction());
//        if(isset($this->map_permissions[$route_action]))
//            $route_action = $this->map_permissions[$route_action];

    }

    protected function successResponse(array $data, $statusCode = 200)
    {
        return successResponse($data, $statusCode);
    }


    protected function errorResponse(array $data, $statusCode = 500)
    {
        return errorResponse($data, $statusCode);
    }

    protected function successResponsePaginated($resource, $model, array $relation = [], $pagintaion = null)
    {
        $pagination = $pagintaion ? $pagintaion : config('defaults.default_pagination');
        return ($resource::collection($model::with($relation)->paginate($pagination)));
    }

    protected function notFoundResponse(array $data, $statusCode = 404)
    {
        return notFoundError($data, $statusCode);
    }

    public function imageUpload($file, $folderpath)
    {
        return uploadImage($file, $folderpath);
    }

    public function removeImage($folderpath)
    {
        return removeImage($folderpath);
    }

    public function getSearchPaginated($resource, $model, Request $request, $searchKeys, array $relations = [], array $searchRelationsKeys = [])
    {
        $data = $request->data;
        $relationKeysArr = [];
        foreach ($searchRelationsKeys as $relation => $searchRelationKeys) {
            foreach ($searchRelationKeys as $key => $dbColumn)
                $relationKeysArr[$key] = $relation;
        }
        $model = $model::with($relations);
        if (is_array($data) && !empty($data)) {
            $model->where(function ($query) use ($data, $searchKeys, $relationKeysArr, $searchRelationsKeys) {
                foreach ($data as $key => $value) {
                    $value = strtolower($value);
                    if (in_array($key, $searchKeys)) {
                        $query->whereRaw('lower(' . $key . ') like (?)', ["%$value%"]);
                    }
                    elseif (isset($relationKeysArr[$key])) {
                        $relation = $relationKeysArr[$key];
                        $dbColumn = $searchRelationsKeys[$relation][$key];
                        $query->whereHas($relation, fn($query) => $query->whereRaw('lower(' . $dbColumn . ') like (?)', ["%$value%"]));
                    }
                }
            });
        }
        $rows = $model->paginate($request->limit ?? config('defaults.default_pagination'));


        return $resource::collection($rows);

    }

    public function getLocaleTranslation($model, $key)
    {
        return getLocaleTranslation($model, $key);
    }
}


