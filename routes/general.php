<?php

use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\Currency\CurrencyController;
use App\Http\Controllers\Fields\FieldsController;
use App\Http\Controllers\Label\LabelController;
use App\Http\Controllers\Language\LanguageController;
use App\Http\Controllers\Prices\PricesController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Tag\TagController;
use App\Http\Controllers\Tax\TaxController;
use App\Http\Controllers\Unit\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('brands',[BrandController::class,'getBrandsData']);
Route::get('categories',[CategoryController::class,'getCategoiresData']);
Route::get('countries',[CountryController::class,'getCountriesData']);
Route::get('currencies',[CurrencyController::class,'getCurrenciesData']);
Route::get('fields',[FieldsController::class,'getFieldsData']);
Route::get('labels',[LabelController::class,'getLabelsData']);
Route::get('languages',[LanguageController::class,'getLanguagesData']);
Route::get('tags',[TagController::class,'getTagsData']);
Route::get('units',[UnitController::class,'getUnitsData']);
Route::get('taxes',[TaxController::class,'getTaxesData']);
Route::get('settings',[SettingsController::class,'getSettingsData']);
Route::get('prices',[PricesController::class,'getPricesData']);