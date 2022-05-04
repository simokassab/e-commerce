<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Field;

class FieldValue extends Model
{
    use HasFactory;
    protected $table='fields_values';

    public function field(){
        return $this->belongsTo(Field::class,'id','field_id');
        }
}
