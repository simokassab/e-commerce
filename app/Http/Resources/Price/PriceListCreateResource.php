<?php

namespace App\Http\Resources\Price;

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
//        $priceClassIds = Arr::pluck($this::$data, 'id');
        $priceClasses = [];
        $priceListsWasLoaded = $this->relationLoaded('profile') ? true : false;
        if(!$priceListsWasLoaded){
            foreach ($this::$data as $priceList){
                    $priceClasses['price_'.$priceList['id']] = [
                        'id' => null,
                        'price' => 0 ,
                        'UOM' => $this->whenLoaded('unit')->code ?? '-',
                        'is_virtual' => (bool)$priceList['is_virtual'],
                    ];

            }
        }
        $priceClasses =  $this->whenLoaded('pricesList', function ()use($priceClasses){
            $priceLists =  $this->whenLoaded('pricesList');
            foreach ($priceLists as $priceList){
                $price = $priceList->load('prices')->prices;
                if($price){
                    $priceClasses['price_'.$price->id] = [
                        'id' => $priceList->id,
                        'price' => $priceList->price,
                        'UOM' => $this->whenLoaded('unit')->code ?? '-',
                        'is_virtual' => (bool)$price->is_virtual,
                    ];

                }

            }
            return $priceClasses;
        });


       $productArray = [
           'code' => $this->code,
           'item' => $this->name,
           'UOM' => $this->whenLoaded('unit_id'),
       ];

        return array_merge($productArray,$priceClasses);

    }

    public static function customCollection($resource, $data): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        //you can add as many params as you want.
        self::$data = $data;
        return parent::collection($resource);
    }
}
