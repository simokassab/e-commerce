<?php

namespace App\Models\RolesAndPermissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class CustomPermission extends Permission
{
    use HasFactory;
}
