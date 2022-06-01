<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Route::macro('customBrandResource', function ($uri, $controller) {
            Route::get("{$uri}/order",[$controller,'getAllBrandsSorted']);
            Route::patch("{$uri}/toggle-status/{id}",[$controller,'toggleStatus']);
            Route::get("{$uri}/update-order",[$controller,'updateSortValues']);

            Route::resource($uri, $controller);
        });



    }
}
