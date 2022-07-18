<?php

namespace App\Models\Product;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class ProductPrice extends Model
{
    use HasFactory;
    protected $table = 'products_prices';

    public static function inhertPrices(Request $request,$childrenIds){
        if ($request->has('prices')) {
            $pricesArray = $request->prices ?? [];
            $childrenArray = [];
            foreach ($childrenIds as $childId => $value) {
                $childrenArray[$childId]["price_id"] =$pricesArray[0]["price_id"];
                $childrenArray[$childId]["price"] =  $pricesArray[0]["price"];
                $childrenArray[$childId]["discounted_price"] = $pricesArray[0]["discounted_price"];
                $childrenArray[$childId]["product_id"] = $childrenIds[$childId];
                $childrenArray[$childId]["created_at"] = Carbon::now()->toDateTimeString();
                $childrenArray[$childId]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductPrice::insert($childrenArray);
        }
    }

}
