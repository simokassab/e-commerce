<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductImage extends Model
{
    use HasFactory;
    protected $table='products_images';

    public function productImages(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
