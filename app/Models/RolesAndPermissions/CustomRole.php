<?php

namespace App\Models\RolesAndPermissions;

use App\Services\RolesAndPermissions\RolesAndPermissionsService;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class CustomRole extends Role
{
    use HasFactory;

    public function parent(){
        return $this->belongsTo($this,'parent_id','id');
    }

    // this function only returns the nearst children and dones'nt dig deeper into the relation
    public function children(){
        return $this->hasMany($this,'parent_id','id');
    }

    public function allChildren($flatten = false){
        //this function will get all of the children and there children also
        return RolesAndPermissionsService::getRoleChildren($this->id, $flatten);
    }

    public function checkIfParentHasPermission(Int | Permission $permission){
        $permission = (is_numeric($permission) ? $permission : $permission->id);
        return $this->parent->hasPermissionTo($permission);
    }



    public function setParent(Role | int $parent){
        $roleId = (is_numeric($parent) ? $parent : $parent->id);

        if($roleId == $this->id){
            return false;
        }

        $this->parent_id = $roleId;
        if($this->save()){
            return $this;
        }
        return false;

    }

    public function detatchParent(){
        $this->parent_id = null;
        if($this->save()){
            return $this;
        }
        return false;

    }


    public function detachPermissionsForParentRoleAndChildren(Collection $permissions): CustomRole{
        DB::beginTransaction();

        try {
            collect( self::findMany( $this->allChildren(true) ) )->map(function($item) use($permissions){
                $item->revokePermissionTo($permissions);
            });

            $this->revokePermissionTo($permissions);

        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

        DB::commit();
        return $this;
    }

    public function givePermissionsForParentRoleAndChildren(Collection $permissions): CustomRole{

        DB::beginTransaction();

        try {
            collect( self::findMany( $this->allChildren(true) ) )->map(function($item) use($permissions){
                $item->givePermissionTo($permissions);
            });

            $this->givePermissionTo($permissions);

        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

        DB::commit();
        return $this;

    }

    public function canDeleteRole(&$message){
        if( ($this->permissions()->exists())){
            $message = "the role can't be deleted it is attached to permissions!";
            return false;
        }

        if( ($this->users()->exists())){
            $message = "the role can't be deleted it is attached to users!";
            return false;
        }

        return true;

    }





    // public function setChildren(array $children){
    //     DB::beginTransaction();

    //     foreach ($children as $child){
    //         $child->parent_id = $this->id;
    //         if(!$child->save()){
    //             DB::rollBack();
    //             return false;
    //         }
    //     }

    //     DB::commit();
    //     return true; // return true means succes in updating all children
    // }

    // public function ParentOfParent(){

    // }

    // public function parentByLevel(){

    // }

    // public function childrenByLevel(){

    // }


}