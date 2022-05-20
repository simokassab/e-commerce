<?php

namespace App\Models\Discount;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Category\Category;
use App\Models\Tag\Tag;
use App\Models\Brand\Brand;
class Discount extends MainModel
{
    use HasFactory;
    protected $table='discounts';
    protected $guard_name = 'sanctum';

    public function category(){
        return $this->belongsToMany(Category::class,'discounts_entities','discount_id','category_id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class,'discounts_entities','discount_id','tag_id');
    }

    public function brand(){
        return $this->belongsToMany(Brand::class,'discounts_entities','discount_id','brand_id');
    }
    
}
