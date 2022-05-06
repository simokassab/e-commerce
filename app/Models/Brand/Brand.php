<?php

namespace App\Models\Brand;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Label\Label;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;

class Brand extends Model
{
    use HasFactory;
    protected $table='brands';

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
}
