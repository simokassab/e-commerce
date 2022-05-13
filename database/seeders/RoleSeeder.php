<?php

namespace Database\Seeders;

use App\Models\RolesAndPermissions\CustomPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\RolesAndPermissions\RolesAndPermissionsService;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolesAndPermissionsService::createRoles();
    }
}
