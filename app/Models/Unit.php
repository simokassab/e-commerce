<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Brand;

class Unit extends Model
{
    use HasFactory;
    protected $table='units';

    public function cateogry(){
        $this->hasMany(Category::class,'category_id');
    }
    public function product(){
        $this->hasMany(Product::class,'unit_id');
    }
    public function tax(){
        $this->hasMany(Tax::class,'tax_id');
    }
    public function brand(){
        $this->hasMany(Brand::class,'brand_id');
    }
}
