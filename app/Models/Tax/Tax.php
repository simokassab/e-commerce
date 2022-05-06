<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tax\TaxComponent;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Brand\Brand;
class Tax extends Model
{
    use HasFactory;
    protected $table='taxes';

    public function taxComponent(){
        return $this->hasMany(TaxComponent::class,'tax_id');
    }
    public function cateogry(){
        $this->hasMany(Category::class,'category_id');
    }
    public function product(){
        $this->hasMany(Product::class,'tax_id');
    }
    public function tax(){
        $this->hasMany(Tax::class,'tax_id');
    }
    public function brand(){
        $this->hasMany(Brand::class,'brand_id');
    }
}
