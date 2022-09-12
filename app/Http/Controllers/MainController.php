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
use App\Models\Attribute\AttributeValue;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;

class MainController extends Controller
{

    public function __construct( /**  $defaultPermissionsFromChild = null */ )
    {
//        $routeAction = basename(Route::currentRouteAction()); //we got the permission name

//        if (isset($this->map_permissions[$routeAction]))
//            $routeAction = $this->map_permissions[$routeAction];

//        if (!auth()->user()->hasPermissionTo($routeAction)) {
//             $this->errorResponse('you are un authorized for this action' ,returnCode:401);
//        }

//        $this->defaultLocalize = config('app.locale');


    }

    protected function successResponse($message = 'Success!', array $data = [], $returnCode = 1, $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return successResponse($message, $data, $returnCode, $statusCode);
    }

    protected function errorResponse($message = 'An error occurred please try again later', array $data = [], $returnCode = -1, $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return errorResponse($message, $data, $returnCode, $statusCode);
    }

    protected function successResponsePaginated($resource, $model, array $relation = [], $pagintaion = null)
    {
        $pagination = $pagintaion ? $pagintaion : config('defaults.default_pagination');
        return ($resource::collection($model::with($relation)->paginate($pagination)));
    }

    protected function notFoundResponse(array $data, $statusCode = 404)
    {
        return notFoundError($data, [$statusCode]);
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
        $data = $request->data ?? [];
        $relationKeysArr = [];
        foreach ($searchRelationsKeys as $relation => $searchRelationKeys) {
            foreach ($searchRelationKeys as $key => $dbColumn) {
                if (!isset($relationKeysArr[$key]))
                    $relationKeysArr[$key] = [];
                $relationKeysArr[$key][] = $relation;
            }
        }
        $model = $model->with($relations);
        $globalValue = strtolower($request->general_search);
        if (!empty(trim($globalValue))) {
            $model->when($request->has('general_search') && $request->general_search != null, function ($query) use ($searchKeys, $globalValue, $request, $searchRelationsKeys) {
                foreach ($searchKeys as $key => $attribute) {
                    $query->oRwhereRaw('lower(' . $attribute . ') like (?)', ["%$globalValue%"]);
                }

                foreach ($searchRelationsKeys as $relation => $relationKeys) {

                    foreach ($relationKeys as $dbColumn) {
                        $query->oRwhereHas($relation, fn($query) => $query->whereRaw('lower(' . $dbColumn . ') like (?)', ["%$globalValue%"]));
                    }
                }
            });
        }
        if (is_array($data) && !empty($data)) {
            $model->where(function ($query) use ($data, $searchKeys, $relationKeysArr, $searchRelationsKeys,) {
                foreach ($data as $key => $value) {
                    $value = strtolower($value);
                    if (empty(trim($value)))
                        continue;
                    if ((in_array($key, $searchKeys) && !empty($value))) {
                        $query->whereRaw('lower(' . $key . ') like (?)', ["%$value%"]);
                    } elseif (!empty($relationKeysArr[$key])) {
                        $query->where(function ($subQuery) use ($relationKeysArr, $key, $searchRelationsKeys, $value) {
                            foreach ($relationKeysArr[$key] as $key2 => $relation) {
                                $dbColumn = $searchRelationsKeys[$relation][$key];
                                if ($key2 == 0)
                                    $subQuery->whereHas($relation, fn($query) => $query->whereRaw('lower(' . $dbColumn . ') like (?)', ["%$value%"]));
                                else
                                    $subQuery->orWhereHas($relation, fn($query) => $query->whereRaw('lower(' . $dbColumn . ') like (?)', ["%$value%"]));
                            }
                        });
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


