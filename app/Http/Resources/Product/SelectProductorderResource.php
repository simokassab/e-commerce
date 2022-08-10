<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class SelectProductorderResource extends JsonResource
{
    private static $data = [];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //change to array from data
        $priceObject = ($this->whenLoaded('pricesList')->where('price_id',1)->first());
        $price = $priceObject ? $priceObject->price : 0;
        //change to code instead of queries just pass an array of the elements, transform them to a collection and simply use the where function
        $currencySymbol = $priceObject ? ($priceObject->prices ? $priceObject->prices->currency->symbol : '') : '';
        $tax = ($this->whenLoaded('tax')->percentage * $price)/100;
        $taxObject = $this->whenLoaded('tax');
        if($taxObject->is_complex){
            $tax = $taxObject->getComplexPrice($price,self::$data['taxComponents']->toArray(),self::$data['tax']->toArray());
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'tax' => $tax,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image) : 'default_image' ,
            'sku' => $this->sku,
            'price' => $price + $tax,
            'currency_symbol' => $currencySymbol,
            'quantity' => 1,
            'quantity_in_stock_available' => $this->quantity - $this->minimum_quantity < 0 ? 0 : $this->quantity - $this->minimum_quantity,
            'quantity_in_stock' => $this->quantity,

        ];
    }


    public static function customCollection($resource, $data): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        //you can add as many params as you want.
        self::$data = $data;
        return parent::collection($resource);
    }
}
