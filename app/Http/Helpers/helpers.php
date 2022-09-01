<?php

use App\Exceptions\FileErrorException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

function uploadImage($file, $folderPath): bool|string
{
    try {
        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        $path = Storage::putFileAs('public/' . $folderPath, $file, $fileName);
    } catch (\App\Exceptions\FileErrorException $exception) {
        throw new FileErrorException();
    } catch (ValueError $exception) {
        throw $exception;
    } catch (ErrorException $exception) {
        throw new FileErrorException();
    }

    return $realPath = $folderPath . '/' . $fileName;
}

function getAssetsLink($path)
{
    return asset($path);
}

function errorResponse($message = 'An error occurred please try again later', array $data = [], $returnCode = -1, $statusCode = 200): \Illuminate\Http\JsonResponse
{
    $return['message'] = $message;
    $return['data'] = $data;
    $return['code'] = $returnCode;

    return response()->json($return, $statusCode);
}

function successResponse($message = 'Success!', array $data = [], $returnCode = 1, $statusCode = 200): \Illuminate\Http\JsonResponse
{
    $return['message'] = $message;
    $return['data'] = $data;
    $return['code'] = $returnCode;

    return response()->json($return, $statusCode);
}

function notFoundError($message = 'Not found!', array $data = [], $returnCode = -2, $statusCode = 404): \Illuminate\Http\JsonResponse
{
    $return['message'] = $message;
    $return['data'] = $data;
    $return['code'] = $returnCode;

    return response()->json($return, $statusCode);
}

function removeImage($folderpath)
{
    if ((empty($folderpath)) || $folderpath == null) {
        return true;
    }
    if (Storage::exists($folderpath ?? '')) {
        return Storage::delete($folderpath);
    }

    return true;
}

function getLocaleTranslation($model, $key)
{
    return $model->getTranslation($key);
}

function convertFromArrayToString($array, $separator = ',')
{
    return implode($separator, $array);
}

/**
 * @throws Throwable
 */
function getSettings(array | string $key=null) : mixed{
    $settings = Cache::get('settings');
    $availableSettings = $settings->pluck('title');


    if(is_array($key)){
        foreach ($key as $object) {
            throw_if(!in_array($object, $availableSettings->toArray()),new Exception('The ' . $object . ' is not a valid settings'));
        }
        return $settings->whereIn('title',$key);
    }elseif(is_string($key)){
        throw_if(!in_array($key, $availableSettings->toArray()),new Exception('The ' . $key . ' is not a valid settings'));
        return $settings->where('title',$key)->first();
    }else{
        return $settings;
    }
}

function array_to_obj($array, &$obj)
{
    foreach ($array as $key => $value) {
        $id=$value->price_id;
        if (is_array($value)) {
            $obj->{$id} = new stdClass();
            array_to_obj($value, $obj->$key);
        } else {
            $obj->{$id} = $value;
        }

    }
        return $obj;
}

function arrayToObject($array)
{
    $object = new stdClass();
    return array_to_obj($array, $object);

}
