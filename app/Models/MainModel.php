<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainModel extends Model
{
    use HasFactory;


    public function setIsDefault(){
        $this->query()
            ->where('is_default' , 1)
            ->whereNot('id',$this->id)
            ->update(['is_default' => 0]);

        $this->is_default = 1;

        return $this;
    }



}
