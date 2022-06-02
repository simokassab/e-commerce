<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainModel extends Model
{
    use HasFactory;

    protected $guard_name = 'sanctum';



    public function setIsDefault(){
        $this->query()
            ->where('is_default' , 1)
            ->whereNot('id',$this->id)
            ->update(['is_default' => 0]);

        $this->is_default = 1;

        return $this;
    }

    public static function getMaxSortValue(){

        return self::max('sort') + 1;// get the max sort

}

public function scopeOrderBy($query)
    {
        $query->orderByRaw('ISNULL(sort), sort ASC');
    }


}
