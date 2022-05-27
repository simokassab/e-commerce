<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// function uploadImage(Request $request=null,$folderName,$folderType){

//     "/categories/images/";
//     try {
//         $filePathName = $folderName.'/'.$folderType;
//         $fileName = uniqid().'_'.$request->file('image')->getClientOriginalName();
//         $path = Storage::putFileAs($filePathName, $request->file('image'), $fileName);

//         return $path;
//     } catch (\Exception $exception) {
//         throw new Exception();
//     }

// }


function uploadImage($file,$folderpath,$type){

        try {
            $fileName = uniqid().'_'.$file->getClientOriginalName();
            $path = Storage::putFileAs($folderpath, $file, $fileName);
                    return $path;
                } catch (\Exception $exception) {
                    throw new Exception();
                }


}
