<?php

namespace App\Models\Category;

use App\Models\Field\Field;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CategoriesFields extends Model
{
    use HasFactory,HasTranslations;
    protected $translatable=[''];
    protected $table='categories_fields';

    public function field(){
        return $this->hasOne(Field::class,'id','field_id');
    }

}
