<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('create-token',[TestController::class,'getToken']);
Route::get('password', fn () => Hash::make('123456')) ;

