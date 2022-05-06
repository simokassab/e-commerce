<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Field;
use App\Models\Brand;
class FieldValue extends Model
{
    use HasFactory;
    protected $table='fields_values';

    public function field(){
        return $this->belongsTo(Field::class,'field_id');
        }

    public function fieldCategorie(){
        return $this->belongsToMany(Field::class,'categories_fields','field_value_id');
    }

    public function category(){
        return $this->belongsToMany(Category::class,'categories_fields','field_value_id','category_id');
    }

    public function brand(){
        return $this->belongsToMany(Brand::class,'brands_fields','field_value_id','brand_id');
    }
    public function fieldBrand(){
        return $this->belongsToMany(Field::class,'brands_fields','field_value_id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id');

    }
}
