<?php

namespace Database\Seeders;

use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([

            BrandSeeder::class,
            CategorySeeder::class,
            CountrySeeder::class,
            CurrencySeeder::class,
            DiscountEntitySeeder::class,
            DiscountSeeder::class,
            FieldSeeder::class,
            FieldValueSeeder::class,
            LabelSeeder::class,
            LanguageSeeder::class,
            SettingSeeder::class,
            TagSeeder::class,
            UnitSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
