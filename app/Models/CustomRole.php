<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as Role;
use Illuminate\Support\Facades\DB;


class CustomRole extends Role
{
    use HasFactory;

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

    public function setParent(Role | int $parent){
        if($parent->id == $this->id){
            return false;
        }

        if(is_object($parent)){
            $this->parent_id = $parent->id;
            if($this->save()){
                return $this;
            }
            return false;
        }

        $this->parent_id = $parent;
        if($this->save()){
            return $this;
        }
        return false;
    }

    public function parent(){
        return $this->belongsTo($this,'parent_id','id');
    }

    public function children(){
        return $this->hasMany($this,'parent_id','id');
    }

    // public function ParentOfParent(){

    // }

    // public function parentByLevel(){

    // }

    // public function childrenByLevel(){

    // }

    public function allChildren(){

    }
}
