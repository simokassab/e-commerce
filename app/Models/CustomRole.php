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


    public function setParent(Role | int $parent){

        if(is_object($parent)){

            if($parent->id = $this->id){
                return false;
            }

            $this->parent_id = $parent->id;
            if($this->save()){
                return $this;
            }
            return false;
        }

        if(!$this->find($parent) || $this->id == $parent){
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

    // this function only returns the nearst children and dones'nt dig deeper into the relation
    public function children(){
        return $this->hasMany($this,'parent_id','id');
    }

    public function allChildren(){
        $old_count = 0;
        $relations = "children";
        $current_count = collect($this->load($relations))->flatten()->count();

        while($current_count > $old_count){
            $old_count = collect($this->load($relations))->flatten()->count();
            $relations .= ".children";
            $current_count = collect($this->load($relations))->flatten()->count();
        }

        return $this->load($relations);

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
