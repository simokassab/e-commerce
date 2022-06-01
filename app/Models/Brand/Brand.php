<?php

namespace App\Models\Brand;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Label\Label;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;

class Brand extends MainModel
{
    use HasFactory;
    protected $table='brands';
    protected $guard_name = 'sanctum';

    public function label(){
        return $this->belongsToMany(Label::class,'brands_labels','brand_id');

    }

    public function field(){
        return $this->belongsToMany(field::class,'brands_fields','field_id','brand_id');
    }
    public function fieldValue(){
        return $this->belongsToMany(FieldValue::class,'brands_fields','brand_id','field_value_id');


    }
    public function cateogry(){
        $this->hasMany(Categorie::class,'category_id');
    }
    public function product(){
        $this->hasMany(Product::class,'brand_id');
    }
    public function tax(){
        $this->hasMany(Tax::class,'tax_id');
    }
    public function unit(){
        $this->hasMany(Unit::class,'unit_id');
    }

    public static function getMaxSortValue(){

            return self::max('sort') + 1;// get the max sort

    }

}
