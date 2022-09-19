<?php

namespace App\Providers;

use App\Models\Settings\Setting;
use App\Models\User\User;
use App\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Observers\Settings\SettingObserver;
use App\Observers\User\UserObserver;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        [
            [
                'class' => Setting::class,
                'observer' => SettingObserver::class,
            ]
        ];
        Setting::observe(SettingObserver::class);
        User::observe(UserObserver::class);

        // Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
        //     $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

        //     return new LengthAwarePaginator(
        //         $this->forpage($page, $perPage),
        //         $total ?: $this->count(),
        //         $perPage,
        //         $page,
        //         [
        //             'path' => LengthAwarePaginator::resolveCurrentPath(),
        //             'pageName' => $pageName,
        //         ]
        //     );
        // });


    }
}
