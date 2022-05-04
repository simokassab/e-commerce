<?php

namespace App\Models;

use App\Models\Categorie as ModelsCategorie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Label;

class Categorie extends Model
{
    use HasFactory;
    protected $table='categories';

    public function parent(){
        return $this->hasMany(Categorie::class,'parent_id','id');
    }
    public function children(){
        return $this->belongsTo(Categorie::class,'id','parent_id');
    }

    public function labels(){
        // return $this->hasMany('')
    }

}
