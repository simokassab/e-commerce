<?php

namespace Database\Seeders;

use App\Models\RolesAndPermissions\CustomPermission;
use App\Services\RolesAndPermissions\RolesService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Commands\CreatePermission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolesService::createPermissions();
    }
}
