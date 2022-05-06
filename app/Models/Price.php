<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Currency;

class Price extends Model
{
    use HasFactory;
    protected $table='prices';

    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id');

    }
    public function products(){
        return $this->hasMany(Product::class,'product_id');
    }

}
