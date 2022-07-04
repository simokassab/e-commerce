<?php

namespace App\Http\Controllers\Prices;

use App\Http\Controllers\MainController;
use App\Http\Requests\price\PricesRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\PriceResource;
use App\Http\Resources\SettingsResource;
use App\Models\Currency\Currency;
use Illuminate\Http\Request;
use App\Models\Price\Price;

class PricesController extends MainController
{

    const OBJECT_NAME = 'objects.price';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Exception
     */
    public function index(Request $request)
    {
        dd($request->limit);
        if ($request->method()=='POST') {
            $relations=['currency','products','originalPrice','originalPricesChildren'];
            $searchKeys=['name','original_percent'];
            $data=($request->data);
            $keys = array_keys($data);

            $originalPriceName = $data['original_price_name'] ?? '';
            $currency = $data['currency_name'] ?? '';
            $rows = Price::with($relations)
                ->when($request->has('data.original_price_name'),function ($query) use($originalPriceName){
                    $query->whereHas('originalPrice',fn ($query)  => $query->whereRaw('lower(name) like (?)',["%$originalPriceName%"]) );
                })
                ->when($request->has('data.currency_name'), function ($query) use($currency){
                    $query->whereHas('currency',fn ($query) => $query->whereRaw('lower(name) like (?)',["%$currency%"]));
                })
                ->where(function($query) use($keys,$data,$searchKeys){
                    foreach($keys as $key){
                        if(in_array($key,$searchKeys)){
                            $value=strtolower($data[$key]);
                            $query->whereRaw('lower('.$key.') like (?)',["%$value%"]);
                        }
                    }
                })->paginate($request->limit ?? config('defaults.default_pagination'));

            return  PriceResource::collection($rows);


        }
        return $this->successResponsePaginated(PriceResource::class,Price::class,['currency','originalPrice']);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOriginalPrices()
    {
        $originalPrices = Price::with(['originalPrice','currency'])->where('is_virtual',0)->get();
        return $this->successResponse([
            'prices' => PriceResource::collection($originalPrices)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PricesRequest $request)
    {
        $price = new Price();
        $price->name = json_encode($request->name);
        $price->currency_id = $request->currency_id;
        $price->is_virtual = $request->is_virtual;

        if($request->is_virtual){
            $price->original_price_id = $request->original_price_id;
            $price->original_percent = $request->original_percent;
        }

        if($price->save()){
            return $this->successResponse([
                'message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
                'price' => new PriceResource($price->load(['originalPrice','currency']))
            ]);
        }

        return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Price $price)
    {
        return $this->successResponse([
           'price' => new PriceResource($price->load(['originalPrice','currency']))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Price $price
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PricesRequest $request, Price $price)
    {

        $price->name = json_encode($request->name);
        $price->currency_id = $request->currency_id;
        $price->is_virtual = $request->is_virtual;

        if($request->is_virtual){
            $price->original_price_id = $request->original_price_id;
            $price->original_percent = $request->original_percent;
        }

        if($price->save()){
            return $this->successResponse([
                'message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
                'price' => new PriceResource($price->load(['originalPrice','currency']))
            ]);
        }

        return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME)]) ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $id)
    {
        //this module can't be destroyed
    }

}
