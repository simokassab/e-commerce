<?php

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
use App\Http\Controllers\Prices\PricesListController;
use App\Http\Controllers\RolesAndPermissions\RolesController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Tag\TagController;
use App\Http\Controllers\Tax\TaxController;
use App\Http\Controllers\Unit\UnitController;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Prices\PricesController;
use App\Http\Controllers\Product\ProductController;
use \App\Http\Controllers\Orders\OrdersController;
use \App\Http\Controllers\Coupons\CouponsController;

Route::get('brands',[BrandController::class,'getTableHeaders']);
Route::get('categories',[CategoryController::class,'getTableHeaders']);
Route::get('countries',[CountryController::class,'getTableHeaders']);
Route::get('currencies',[CurrencyController::class,'getTableHeaders']);
Route::get('discounts',[DiscountController::class,'getTableHeaders']);
Route::get('fields',[FieldsController::class,'getTableHeaders']);
Route::get('labels',[LabelController::class,'getTableHeaders']);
Route::get('languages',[LanguageController::class,'getTableHeaders']);
Route::get('prices',[PricesController::class,'getTableHeaders']);
Route::post('prices_list',[PricesListController::class,'getTableHeaders']);
Route::get('roles',[RolesController::class,'getTableHeaders']);
Route::get('settings',[SettingsController::class,'getTableHeaders']);
Route::get('tags',[TagController::class,'getTableHeaders']);
Route::get('taxes',[TaxController::class,'getTableHeaders']);
Route::get('units',[UnitController::class,'getTableHeaders']);
Route::get('users',[UsersController::class,'getTableHeaders']);
Route::get('settings',[SettingsController::class,'getTableHeaders']);
Route::get('products',[ProductController::class,'getTableHeaders']);
Route::get('products-select',[ProductController::class,'getTableHeadersForSelect']);
Route::get('orders',[OrdersController::class,'getTableHeaders']);
Route::get('coupons',[CouponsController::class,'getTableHeaders']);


















