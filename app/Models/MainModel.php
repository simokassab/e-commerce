<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainModel extends Model
{
    use HasFactory;


    public function setIsDefault(){
        $query = self::where('is_default',1);

        if($query->exists())
            $query->update(['is_default'=>0]);

        $this->is_default =1;

        return $this;

    }
}
