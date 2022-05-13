<?php

namespace App\Models\Price;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;
use App\Models\Currency\Currency;

class Price extends Model
{
    use HasFactory;
    protected $table='prices';
    protected $guard_name = 'sanctum';

    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id');

    }
    public function products(){
        return $this->hasMany(Product::class,'product_id');
    }

}
