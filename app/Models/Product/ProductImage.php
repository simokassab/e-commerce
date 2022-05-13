<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;

class ProductImage extends Model
{
    use HasFactory;
    protected $table='products_images';
    protected $guard_name = 'sanctum';

    public function productImages(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
