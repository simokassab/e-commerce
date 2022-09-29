<?php

namespace App\Http\Controllers\Prices;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Resources\Price\PriceListCreateResource;
use App\Models\Field\FieldValue;
use App\Models\Price\Price;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use Illuminate\Http\Request;
use App\Support\Collection;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

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
                'sort' => false
            ];
        }

        return $this->successResponse('Success!', ['headers' =>array_merge(__('headers.prices_list'),$pricesHeader) ]);
    }

    public function show(Request $request){
        $request->validate([
            'general_search' => 'nullable',
            'limit' => 'nullable',
            'data.*' => 'nullable',
            'advanced_search.prices_class' => 'required',
            'advanced_search.prices_class.*' => 'required|numeric|exists:prices,id',

        ]);

        $pricesClassRequired = $request->advanced_search['prices_class'];

        $prices = Price::query()->findMany($pricesClassRequired);
        $productPrices = ProductPrice::all();
//        $allPrices = Price::all();

        $allProducts = Product::with(['pricesList.prices.currency','unit'])
            ->where(function ($query)use($request){
                $query->when(!empty($request->general_search) , function($query) use($request){
                $value = $request->general_search;
                $query->whereRaw('lower(code) like (?)', ["%$value%"]);
                $query->orWhereRaw('lower(name) like (?)', ["%$value%"]);
                $query->orWhereHas('unit',function($query)use($request,$value){
                    $query->whereRaw('lower(code) like (?)', ["%$value%"]);
                });
            });
            })
            ->when($request->has('data') && (key_exists('code',$request->data)) , function($query) use($request){
                $value = $request->data['code'];
                $query->whereRaw('lower(code) like (?)', ["%$value%"]);
            })
            ->when($request->has('data') && (key_exists('title',$request->data)) , function($query) use($request){
                $value = $request->data['title'];
                $query->whereRaw('lower(name) like (?)', ["%$value%"]);
            })
            ->when($request->has('data') && (key_exists('UOM',$request->data)) , function($query) use($request){
                $query->whereHas('unit',function($query)use($request){
                    $value = $request->data['UOM'];
                    $query->whereRaw('lower(code) like (?)', ["%$value%"]);
                });
            })
            ->paginate($request->limit ?? config('defaults.default_pagination'));



        return PriceListCreateResource::customCollection($allProducts, $prices, $allProducts, $productPrices);

    }

    public function update(Request $request){
        $request->validate([
            'data.*.code' => 'required|exists:App\Models\Product\Product,code'
        ]);

        DB::beginTransaction();
        try {
            $pricesWithProducts = collect($request->all()['data']);
            $prices = [];

            $products = Product::query()->select('id','code')->whereIn('code',$pricesWithProducts->pluck('code')->toArray())->get();
            $allPrices = Price::all();
            foreach ($pricesWithProducts as $price) {
                $productId = $products->where('code',$price['code'])->first()->id;

                unset($price['code']);
                unset($price['item']);
                unset($price['UOM']);

                foreach ($price as $priceElement) {
                    unset($priceElement['is_virtual']);
                    $priceElement['product_id'] = $productId;

                    $currentPrice = $allPrices->where('id',$priceElement['price_id'])->first();
                    if($currentPrice->is_virtual){
                        continue;
                    }

                    $prices[] = $priceElement;
                }
            }
            ProductPrice::query()->upsert($prices,['id'],['price']);
            DB::commit();

            return $this->successResponse('Prices updates successfully!');
        }catch (\Exception $e){
            DB::rollback();
            return $this->errorResponse('The prices where not updates, try again later!',[
                'error_message' => $e
            ]);
        }


    }
}
