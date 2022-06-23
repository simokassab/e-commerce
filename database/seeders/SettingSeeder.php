<?php

namespace Database\Seeders;

use App\Models\Settings\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Ramsey\Uuid\v1;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->truncate();

        Setting::query()->insert([
            [
            'title' => json_encode(['en' => 'Image size','ar' => 'حجم الصورة']),
            'value' => json_encode(['en' => '10000','ar' => '10000']),
            'is_developer' =>  1,
        ],

        [
            'title' => json_encode(['en' => 'string length','ar' => 'طول السلسلة']),
            'value' => json_encode(['en' => '250','ar' => '250']),
            'is_developer' =>  0,
        ],

        [
            'title' => json_encode(['en' => 'image extensions','ar' => 'أمتدادات الصورة']),
            'value' => json_encode(['en' => 'png,jpg,jpeg','ar' => 'png,jpg,jpeg']),
            'is_developer' =>  1,
        ],
    ]);
    }
}
