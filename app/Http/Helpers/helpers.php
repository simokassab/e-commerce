<?php

use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Boolean;

function uploadImage($file,$folderpath){

        try {
            $fileName = uniqid().'_'.$file->getClientOriginalName();
            $path = Storage::putFileAs($folderpath, $file, $fileName);
                    return $path;
                } catch (\Exception $exception) {
                    throw new Exception();
                }

}

function removeImage($folderpath)
{

   try {
        if(Storage::exists($folderpath)){
           return Storage::delete($folderpath);
        }

        return true;

    } catch (\Exception $exception) {
        throw new Exception();
    }


  }

