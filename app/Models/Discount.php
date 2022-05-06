<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $table='discounts';

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
