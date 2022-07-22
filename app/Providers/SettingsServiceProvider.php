<?php

namespace App\Providers;

use App\Models\Settings\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
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
        Cache::rememberForever('settings', function () {
            $setting=Setting::all(['title','type','value']);
            return $setting;
        });

        // dd($titlesArray);
        // Cache::forever('settingTitles', data_get($setting,'*.title'));
        // Cache::forever('settingTypes', data_get($setting,'*.type'));

        // dd(Cache::get('settingTypes'));

        // Cache::rememberForever('settingsData', function ($setting) {
        //     data_get($setting,'*.title');
        // });
        // config()->set('setting.title',data_get($setting,'*.title'));
        // config()->set('setting.type',data_get($setting,'*.type'));

    }
}
