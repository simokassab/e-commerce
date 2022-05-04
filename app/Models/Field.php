<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FieldValue;

class Field extends Model
{
    use HasFactory;
    protected $table='fields';

    public function fieldsValues(){
        return $this->hasMany(fieldValue::class,'field_id','id');
    }

}
