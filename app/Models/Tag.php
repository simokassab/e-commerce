<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Brand;
use App\Models\Product;
class Tag extends Model
{
    use HasFactory;
    protected $table='tags';

    public function category(){
        return $this->belongsToMany(Category::class,'discounts_entities','tag_id','category_id');
    }

    public function discount(){
        return $this->belongsToMany(Discount::class,'discounts_entities','tag_id','discount_id');
    }

    public function brand(){
        return $this->belongsToMany(Brand::class,'discounts_entities','tag_id','brand_id');
    }
    public function product(){
        return $this->hasMany(Product::class,'product_id');

    }
}
