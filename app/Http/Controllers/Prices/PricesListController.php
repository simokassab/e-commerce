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
    public function getTableHeaders(Request $request){
        if($request->has('prices_class')){

        $prices = Price::findMany($request->prices_class);
        $pricesHeader = [];
        foreach ($prices as $price){
            $pricesHeader['price_'.$price->id] = [
                'is_show' => true,
                'name' => $price->getTranslation('name','en'),
                'search' => 'string',
                'type' => 'string',
                'sort' => true
            ];
        }

        }else{
            $pricesHeader['price'] =[
                'is_show' => true,
                'name' => 'Price',
                'search' => 'string',
                'type' => 'string',
                'sort' => true
            ];
        }

        return $this->successResponse('Success!', ['headers' =>array_merge(__('headers.prices_list'),$pricesHeader) ]);
    }

    public function show(Request $request){
        $products = [];
        $pricesClassesProducts = [];
        $prices = collect([]);

        if(count($request->advanced_search['prices_class']) == 0){
            return $this->successResponse('success',[
                'prices' => PriceListCreateResource::customCollection($products,$prices->toArray())

                ]

            );
        }

        if($request->advanced_search['prices_class']){
            $prices = Price::with(['products'])->findMany($request->advanced_search['prices_class'] ?? []);
            $pricesClassesProducts = $prices->pluck('products');
        }


        foreach ($pricesClassesProducts as $products){
            foreach ($products as $product) {
                $products[] = $product;
            }
        }

        $products = Product::with(['pricesList.prices.currency','unit'])
            ->whereIn('id',collect($products)->unique()->pluck('id'))
            ->orWhereNotIn('id',collect($products)->unique()->pluck('id'))
            ->paginate();

        return PriceListCreateResource::customCollection($products,$prices->toArray());

    }

    public function store(Request $request){

    }
}
