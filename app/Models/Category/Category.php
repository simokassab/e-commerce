<?php

namespace App\Models\Category;

use App\Models\Category as ModelsCategorie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Label\Label;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use App\Models\Tag\Tag;
use App\Models\Discount\Discount;
use App\Models\Brand\Brand;
class Category extends Model
{
    use HasFactory;
    protected $table='categories';
    protected $guard_name = 'sanctum';

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }
    public function children(){
        return $this->hasMany(Category::class,'parent_id');
    }

    public function label(){
        return $this->belongsToMany(Label::class,'categories_labels','category_id');
    }

    public function fields(){
        return $this->belongsToMany(Field::class,'categories_fields','category_id');
    }

    public function fieldValue(){
        return $this->belongsToMany(FieldValue::class,'categories_fields','category_id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class,'discounts_entities','category_id','tag_id');
    }

    public function discount(){
        return $this->belongsToMany(Discount::class,'discounts_entities','category_id','discount_id');
    }

    public function brand(){
        return $this->belongsToMany(Brand::class,'discounts_entities','category_id','brand_id');
    }

    public function productCategory(){
        return $this->hasMany(Product::class,'product_id');

    }
}
