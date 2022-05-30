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
                }
}

function removeImage($folderpath)
{
        if(Storage::exists($folderpath)){
           return Storage::delete($folderpath);
        }

        return true;
  }

