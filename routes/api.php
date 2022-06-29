<?php

use App\Http\Controllers\Attribute\AttributeController;
use App\Http\Controllers\Attribute\AttributeValueController;
use App\Http\Controllers\AuthenticationController;
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
use App\Http\Controllers\Tax\TaxController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Unit\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
$dashboardMiddleware = ['auth:sanctum','localization'];

if( env('APP_DEBUG') ){
    $key = array_search('auth:sanctum', $dashboardMiddleware);
    unset( $dashboardMiddleware[$key] );
}

Route::post('login', [AuthenticationController::class,'login'])->name('login');
Route::get('logout', [AuthenticationController::class,'logout'])->name('logout');

Route::group([ 'prefix' => 'dashboard','middleware' => $dashboardMiddleware ],function (){

    //Permission

    // Routes Macro
    Route::customBrandResource('brand', BrandController::class);
    Route::customCategoryResource('category', CategoryController::class);
    Route::customLanguageResource('language',LanguageController::class);
    //End of Routes Macro

    Route::apiResource('country',CountryController::class);
    Route::post('country/all',[CountryController::class,'index']); // for search

    Route::apiResource('currency',CurrencyController::class);
    Route::patch('currency/set-is-default/{id}',[CurrencyController::class,'setCurrencyIsDefault']);
    Route::post('currency/all',[CurrencyController::class,'index']); // for search

    Route::apiResource('discount',DiscountController::class);
    Route::post('discount/all',[DiscountController::class,'index']); // for search

    Route::apiResource('category',CategoryController::class);
    Route::apiResource('brand',BrandController::class);
    Route::apiResource('discount-entity',DiscountEntityController::class);
    Route::post('discount-entity/all',[DiscountEntityController::class,'index']); // for search

    Route::apiResource('field',FieldsController::class);
    Route::post('field/all',[FieldsController::class,'index']);// for search

    Route::apiResource('field-value',FieldValueController::class);
    Route::post('field-value/all',[FieldValueController::class,'index']);// for search

    Route::apiResource('label',LabelController::class);
    Route::post('label/all',[LabelController::class,'index']);// for search

    Route::apiResource('role',RolesController::class);
    Route::post('role/all',[RolesController::class,'index']);// for search
    Route::get('get-nested-permissions-for-role/{role}',[RolesController::class,'getNestedPermissionsForRole']);

    Route::apiResource('setting',SettingsController::class);
    Route::post('setting/all',[SettingsController::class,'index']);// for search

    Route::apiResource('tag',TagController::class);
    Route::post('tag/all',[TagController::class,'index']);// for search

    Route::apiResource('unit',UnitController::class);
    Route::post('unit/all',[UnitController::class,'index']);// for search


    // @TODO: make a correct function for the user profile
    Route::get('/profile', fn() =>  auth()->user());

    Route::apiResource('user',UsersController::class);
    Route::post('user/all',[UsersController::class,'index']);//for searching

    Route::apiResource('tax',TaxController::class);

    Route::get('test',[TestController::class,'test']);

});

// @TODO: check the name of link and why the controller functions are not set
Route::get('s',[AttributeController::class,'serachdata']);
