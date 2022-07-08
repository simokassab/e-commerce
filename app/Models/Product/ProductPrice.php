<?php

namespace App\Models\Product;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Integer;

class ProductPrice extends Model
{
    use HasFactory;
    protected $table = 'products_prices';

    public static function inhertPrices($parentProductId,$productId){
        $parentPrices = ProductPrice::where('product_id',$parentProductId)->get()->toArray();

        if (count($parentPrices) <= 0) {
            return;
        }

        foreach ($parentPrices as $price => $value) {
            $pricesArray[$price]["product_id"] = $productId;
            $pricesArray[$price]["price_id"] = $value['price_id'];
            $pricesArray[$price]["price"] = $value['price'];
            $pricesArray[$price]["discounted_price"] = $value['discounted_price'];
            $pricesArray[$price]["created_at"] = Carbon::now()->toDateTimeString();
            $pricesArray[$price]["updated_at"] = Carbon::now()->toDateTimeString();
        }

        ProductPrice::insert($pricesArray);
    }

}
