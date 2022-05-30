<?php

use App\Http\Controllers\Attribute\AttributeController;
use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\Currency\CurrencyController;
use App\Http\Controllers\Discount\DiscountController;
use App\Http\Controllers\Discount\DiscountEntityController;
use App\Http\Controllers\Fields\FieldsController;
use App\Http\Controllers\Fields\FieldValueController;
use App\Http\Controllers\Label\LabelController;
use App\Http\Controllers\Language\LanguageController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RolesAndPermissions\RolesController;
use App\Http\Controllers\RolesAndPermissions\PermissionController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Tag\TagController;
use App\Http\Controllers\Unit\UnitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Auth;

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

Route::group([ 'prefix' => 'dashboard','middleware' => ['auth:sanctum','localization'] ],function (){

    //here goes all the routes inside the dashboard
    Route::apiResource('roles',RolesController::class);
    Route::get('get-nested-permissions/{permission}',[PermissionController::class,'getNestedPermissions']);
    Route::apiResource('settings',SettingsController::class);
    Route::apiResource('tag',TagController::class);
    Route::apiResource('attribute',AttributeController::class);
    Route::apiResource('language',LanguageController::class);
    Route::apiResource('fields',FieldsController::class);
    Route::apiResource('field-value',FieldValueController::class);
    Route::apiResource('currency',CurrencyController::class);
    Route::apiResource('labels',LabelController::class);
    Route::apiResource('country',CountryController::class);
    Route::apiResource('category',CategoryController::class);
    Route::apiResource('brand',BrandController::class);
    Route::apiResource('discount',DiscountController::class);
    Route::apiResource('discount-entity',DiscountEntityController::class);

    //change language for dashboard and get the dashborad translated objects
    Route::put('change-language/{lang}',[LanguageController::class,'setLanguage']);

    Route::patch('toggle-status/{id}',[CategoryController::class,'toggleStatus']);
    Route::get('parents',[CategoryController::class,'getAllParentsSorted']);
    Route::get('children/{parent_id}',[CategoryController::class,'getAllChildsSorted']);
    Route::get('update-category-order',[CategoryController::class,'updateSortValues']);

    // Route::post('category-update/{category}',[CategoryController::class,'update']);

});
Route::get('test',[MainController::class,'test']);
