<?php

namespace App\Models\Field;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Field\FieldValue;
use App\Models\Category\Category;
use App\Models\Brand\Brand;
use App\Models\Product\Product;
class Field extends Model
{
    use HasFactory;
    protected $table='fields';

    public function fieldValue(){
        return $this->hasMany(fieldValue::class,'field_value_id');
    }
    public function category(){
        return $this->belongsToMany(Category::class,'categories_fields','field_id','category_id');
    }
    public function fieldValueCategoire(){
        return $this->belongsToMany(FieldValue::class,'categories_fields','field_id');
    }


    public function brand(){
        return $this->belongsToMany(Brand::class,'brands_fields','field_id','brand_id');
    }
    public function fieldValueBrand(){
        return $this->belongsToMany(FieldValue::class,'brands_fields','field_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');

    }
}