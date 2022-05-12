<?php

use App\Http\Controllers\Attribute\AttributeController;
use App\Http\Controllers\RolesAndPermissions\RolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\Currency\CurrencyController;
use \App\Http\Controllers\Labls\LablsController;
use App\Http\Controllers\Language\LanguageController;
use App\Http\Controllers\Tag\TagController;
use App\Http\Controllers\FieldsController;
use App\Http\Controllers\RolesAndPermissions\PermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::apiResource('currency',CurrencyController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([ 'prefix' => 'dashboard','middleware' => 'auth:sanctum'],function (){

    //here goes all the routes inside teh dashboard
    Route::apiResource('roles',RolesController::class);
    Route::apiResource('country',CountryController::class);
    Route::apiResource('labels',LablsController::class);
    Route::apiResource('language',LanguageController::class);
    Route::apiResource('tag',TagController::class);
    Route::apiResource('attribute',AttributeController::class);
    Route::apiResource('fields',FieldsController::class);
    Route::get('create-permission' , [PermissionController::class, 'test']);


});


