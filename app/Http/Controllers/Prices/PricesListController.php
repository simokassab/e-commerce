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
        if(count($request->prices_class) > 0 && ($request->prices_class) != null ){
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
            return $this->successResponse();
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

    public function update(Request $request){
        try {
            $pricesWithPricesClasses = ($request->data);
            $newPrices = [];
            $pricesToBeSaved = [];
            $i = 0;
            foreach ($pricesWithPricesClasses as $pricesWithPricesClass){
                $code = $pricesWithPricesClass['code'];
                unset($pricesWithPricesClass['UOM']);
                unset($pricesWithPricesClass['item']);
                unset($pricesWithPricesClass['code']);
                $newPrices[$i] = $pricesWithPricesClass;
                $newPrices[$i]['code'] = $code;

                $i++;

            }

            foreach ($newPrices as $innerNewPrices){
                $code = $innerNewPrices['code'];
                $productId = Product::where('code' ,$code )->first()->id;
                foreach($innerNewPrices as $innerInnerNewPrice){
                    if(gettype($innerInnerNewPrice) == 'array'){
                        $innerInnerNewPrice['code'] = $code;
                        $innerInnerNewPrice['product_id'] = $productId;
                        $pricesToBeSaved[] = $innerInnerNewPrice;
                    }

                }

            }
            $pricesWithIds = (collect($pricesToBeSaved)->whereNotNull('id')->map(fn($value)=> (collect($value)->forget('is_virtual')->forget('code') )));
            $pricesWithNull = (collect($pricesToBeSaved)->whereNull('id'));
            $codes = $pricesWithNull->pluck('code');
            $productsCodesAndIds = Product::select('code','id')->whereIn('code',$codes)->get();
            $newPrices = [];
            foreach ($pricesWithNull as $priceWithNull){
                foreach($productsCodesAndIds as $productCodeAndIds){
                    $price = [];
                    if($priceWithNull['code'] == $productCodeAndIds['code']){
                        $price['price'] = $priceWithNull['price'];
                        $price['price_id'] = $priceWithNull['price_id'];
                        $price['product_id'] = $productCodeAndIds['id'];
                        $price['created_at'] = now();
                        $price['updated_at'] = now();

                    }
                    $newPrices[] = $price;
                }
            }

            return $newPrices;
            if(count($newPrices) != 0){
                ProductPrice::insert($newPrices);
            }
            if(count($pricesWithIds->toArray()) > 0){
                batch()->update(new ProductPrice(),$pricesWithIds->toArray(),'id');
            }

            return $this->successResponse('the prices have been updated successfully');
        }catch (\Exception $e){
            dd($e);
            return $this->errorResponse('error occurred please try again later!');
        }


    }
}
