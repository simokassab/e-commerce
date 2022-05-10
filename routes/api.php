<?php

use App\Http\Controllers\RolesAndPermissions\RolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\Currency\CurrencyController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([ 'prefix' => 'dashboard','middleware' => 'auth:sanctum'],function (){

    //here goes all the routes inside teh dashboard
    Route::apiResource('roles',RolesController::class);
    Route::apiResource('country',Countries::class);
    Route::apiResource('currency',CurrencyController::class);

});


