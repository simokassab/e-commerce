<?php

namespace App\Models\Unit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Tax\Tax;
use App\Models\Brand\Brand;

class Unit extends MainModel
{
    use HasFactory;
    protected $table='units';
    protected $guard_name = 'sanctum';

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
