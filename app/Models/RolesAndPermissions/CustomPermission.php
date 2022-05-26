<?php

namespace App\Models\RolesAndPermissions;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\RolesAndPermissions\PermissionsServices;
use Spatie\Permission\Models\Permission;

class CustomPermission extends Permission
{
    use HasFactory;
    protected $guard_name = 'sanctum';

    public function allChildren($flatten = false){
        //this function will get all of the children and their nested children also
        return PermissionsServices::getPermissionChildren($this->id, $flatten);
    }

}
