<?php

namespace App\Http\Resources\Price;

use App\Models\Price\Price;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PriceListCreateResource extends JsonResource
{
    private static $data;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $priceClasses = [];
        $neededPricesIds = collect($this::$data)->pluck('id');

        if(( count($this->pricesList->toArray()) == 0 ) ){
            foreach ($this::$data as $priceList){

                    $priceClasses['price_'.$priceList['id']] = [
                        'id' => null,
                        'price' => (double)($this->getPrice($priceList['id'])) ,
                        'price_id' => $priceList['id'],
                        'is_virtual' => (bool)$priceList['is_virtual'],
                    ];

            }
        }
        $priceClassesArray = [];

        $priceClassesArray =  $this->whenLoaded('pricesList', function ()use($priceClasses,$neededPricesIds){
            $availablePrices = [];

            $priceLists =  $this->whenLoaded('pricesList');
            foreach ($priceLists as $priceList){
                $price = $priceList->load('prices')->prices;
                if($price && in_array($price->id, $neededPricesIds->toArray())){
                    $availablePrices[] = $price->id;
                    $priceClasses['price_'.$price->id] = [
                        'id' => $priceList->id,
                        'price' => (double)$this->getPrice($price->id) ,
                        'price_id' => $price->id,
                        'is_virtual' => (bool)$price->is_virtual,
                    ];
                }
            }

            $result = array_diff( $neededPricesIds->toArray(),$availablePrices);

            foreach ($result as $item) {
                $item = collect($this::$data)->where('id',$item)->first();
                $priceClasses['price_'.$item['id']] = [
                    'id' => null,
                    'price' => (double)0,
                    'price_id' => $item['id'],
                    'is_virtual' => (bool)$item['is_virtual'],
                ];
            }
            return collect($priceClasses)->sortBy('price_id')->toArray();
        });


       $productArray = [
           'code' => $this->code,
           'item' => $this->name,
           'UOM' => $this->whenLoaded('unit')->code ?? '-',
       ];

        return array_merge($productArray,$priceClassesArray);

    }

    public static function customCollection($resource, $data): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        //you can add as many params as you want.
        self::$data = $data;
        return parent::collection($resource);
    }
}
