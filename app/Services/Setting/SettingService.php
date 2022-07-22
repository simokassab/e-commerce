<?php

namespace App\Services\Setting;

use App\Models\Settings\Setting;

class SettingService
{
    public static function getSetting($title)
    {
        $setting = Setting::all('title');
        if ($setting) {
            return $setting->value;
        }
        return null;
    }

}
