<?php

use App\Exceptions\FileErrorException;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Boolean;

function uploadImage($file,$folderpath){

    try {
        $fileName = uniqid().'_'.$file->getClientOriginalName();
        $path = Storage::putFileAs($folderpath, $file, $fileName);
        return $path;
    }catch (\App\Exceptions\FileErrorException $exception) {
        throw new FileErrorException();
    }catch(ValueError $exception){
        throw $exception;
    }catch(ErrorException $exception){
        throw new FileErrorException();
    }
}

function errorResponse(Array $data=[],$message = 'an error occurred please try again later',$returnCode = -1, $statusCode= 500){
    $return['message'] = $message;
    $return['data'] = $data;
    $return['return_code'] = $returnCode;

    return response()->json([
        $return
    ],$statusCode);
}

function successResponse(Array $data, $statusCode= 200){
//    $data['success'] = true;
    return response()->json([
        'data' => $data
    ],$statusCode);
}

function notFoundError(Array $data, $statusCode= 400){
    $data['success'] = false;
    return response()->json([
        'data' => $data
    ],$statusCode);
}
function removeImage($folderpath)
{
        if(Storage::exists($folderpath)){
           return Storage::delete($folderpath);
        }

        return true;
  }

  function getLocaleTranslation($model,$key)
{
    return $model->getTranslation($key);

  }

