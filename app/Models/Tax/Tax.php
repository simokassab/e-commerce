<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Tax\TaxComponent;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Brand\Brand;
use Spatie\Translatable\HasTranslations;

class Tax extends MainModel
{
    use HasFactory,HasTranslations;

    protected $translatable=['name'];
    protected $table='taxes';
    protected $guard_name = 'web';

    public function taxComponents(){
        return $this->hasMany(TaxComponent::class,'tax_id');
    }
    public function cateogry(){
        return $this->hasMany(Category::class,'category_id');
    }
    public function product(){
        return $this->hasMany(Product::class,'tax_id');
    }
    public function tax(){
        return $this->hasMany(Tax::class,'tax_id');
    }
    public function brand(){
        return $this->hasMany(Brand::class,'brand_id');
    }
}
