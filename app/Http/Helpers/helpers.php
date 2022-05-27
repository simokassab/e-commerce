<?php

function test(){
    return dd('hello world');
}

function errorResponse(Array $data, $statusCode= 500){
    $data['success'] = false;
    return response()->json([
        'data' => $data
    ],$statusCode);
}

function successResponse(Array $data, $statusCode= 200){
    $data['success'] = true;
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
