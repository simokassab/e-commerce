<?php

namespace App\Models\Product;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use App\Models\Price\Price;
class ProductPrice extends Model
{
    use HasFactory;
    protected $table = 'products_prices';


    public function prices(){
        return $this->hasOne(Price::class,'id','price_id');
    }
}
