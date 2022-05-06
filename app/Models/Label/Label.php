<?php

namespace App\Models\Label;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category\Category;
use App\Models\Brand\brand;
use App\Models\Product\Product;
class Label extends Model
{
    use HasFactory;
    protected $table='labels';

    public function category(){
        return $this->belongsToMany(Category::class,'categories_labels','label_id','category_id');
    }
    public function brand(){
        return $this->belongsToMany(brand::class,'brands_labels','label_id');

    }
    public function product(){
        return $this->hasMany(Product::class,'product_id');

    }
}