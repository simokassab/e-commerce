<?php

namespace App\Http\Controllers\Prices;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Resources\Price\PriceListCreateResource;
use App\Models\Price\Price;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use Illuminate\Http\Request;
use App\Support\Collection;

class PricesListController extends MainController
{
    public function getTableHeaders(){
        return $this->successResponse('Success!', ['headers' => __('headers.prices_list') ]);
    }

    public function create(Request $request){

        $prices = Price::with('products')->findMany($request->prices_class);
        $pricesClassesProducts = $prices->pluck('products');

        foreach ($pricesClassesProducts as $products){
            foreach ($products as $product) {
                $products[] = $product;
            }
        }

        $products = Product::with('pricesList.prices.currency')
            ->whereIn('id',collect($products)->unique()->pluck('id'))
            ->orWhereNotIn('id',collect($products)->unique()->pluck('id'))
            ->paginate();

        return PriceListCreateResource::customCollection($products,$prices->toArray());

    }

    public function store(Request $request){

    }
}
