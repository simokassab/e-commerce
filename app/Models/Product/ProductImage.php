<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Product\Product;
use Spatie\Translatable\HasTranslations;

class ProductImage extends MainModel
{
    use HasFactory,HasTranslations;
    protected $table='products_images';
    protected $guard_name = 'web';
    protected array $translatable = ['title'];

    public function productImages(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
