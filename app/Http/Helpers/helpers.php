<?php

use App\Exceptions\FileErrorException;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Boolean;

function uploadImage($file,$folderpath): bool|string
{
    try {
        $fileName = uniqid().'_'.$file->getClientOriginalName();
        $path = Storage::putFileAs('public/'.$folderpath, $file, $fileName);

    }catch (\App\Exceptions\FileErrorException $exception) {
        throw new FileErrorException();
    }catch(ValueError $exception){
        throw $exception;
    }catch(ErrorException $exception){
        throw new FileErrorException();
    }

    return $realPath = $folderpath .'/'.$fileName;

}

function getAssetsLink($path){
    return asset($path);
}

function errorResponse($message = 'An error occurred please try again later', Array $data=[],$returnCode = -1, $statusCode= 200): \Illuminate\Http\JsonResponse
{
    $return['message'] = $message;
    $return['data'] = $data;
    $return['code'] = $returnCode;

    return response()->json($return,$statusCode);
}

function successResponse($message = 'Success!',Array $data=[],$returnCode = 1, $statusCode= 200): \Illuminate\Http\JsonResponse
{
    $return['message'] = $message;
    $return['data'] = $data;
    $return['code'] = $returnCode;

    return response()->json($return,$statusCode);

}

function notFoundError($message = 'Not found!',Array $data=[],$returnCode = -2, $statusCode= 404): \Illuminate\Http\JsonResponse
{
    $return['message'] = $message;
    $return['data'] = $data;
    $return['code'] = $returnCode;

    return response()->json($return,$statusCode);
}

function removeImage($folderpath)
{
    if( (empty($folderpath)) || $folderpath == null){
        return true ;
    }
    if(Storage::exists($folderpath ?? '')){
        return Storage::delete($folderpath);
    }

    return true;
}

function getLocaleTranslation($model,$key)
{
    return $model->getTranslation($key);

}

function convertFromArrayToString($array,$separator=',')
{
    return implode($separator,$array);
}
