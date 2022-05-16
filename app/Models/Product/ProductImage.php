<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Product\Product;

class ProductImage extends MainModel
{
    use HasFactory;
    protected $table='products_images';
    protected $guard_name = 'sanctum';

    public function productImages(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
