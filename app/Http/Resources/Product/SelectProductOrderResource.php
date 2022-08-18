<?php

namespace App\Http\Resources\Product;

use App\Models\Settings\Setting;
use Hamcrest\Core\Set;
use Illuminate\Http\Resources\Json\JsonResource;

class SelectProductOrderResource extends JsonResource
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
        $isAllowNegativeQuantity  = Setting::query()->where('title','allow_negative_quantity')->first()->value;

        $priceObject = ($this->whenLoaded('pricesList')->where('price_id',1)->first());
        $price = $priceObject ? $priceObject->price : 0;
        //@TODO:change to code instead of queries just pass an array of the elements, transform them to a collection and simply use the where function
        $currencySymbol = $priceObject ? ($priceObject->prices ? $priceObject->prices->currency->symbol : '') : '';
        $tax = ($this->whenLoaded('tax')->percentage * $price)/100;
        $taxObject = $this->whenLoaded('tax');
        if($taxObject->is_complex){
            $tax = $taxObject->getComplexPrice($price,self::$data['taxComponents']->toArray(),self::$data['tax']->toArray());
        }

        $quantity = $this->quantity;
        $preOrder = false;

        if(!$isAllowNegativeQuantity){
            if(($this->pre_order)){
                $preOrder = true;
                $quantity = '--';
            }
        }else{
            $preOrder = true;
            $quantity = '--';

        }

        if($this->type == 'service'){
            $preOrder = true;
        }



        return [
            'id' => $this->id,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image) : 'default_image' ,
            'name' => $this->name,
            'quantity' => 1,
            'tax' => $tax,
            'sku' => $this->sku,
            'price' => $price + $tax,
            'currency_symbol' => $currencySymbol,
            'quantity_in_stock' => $quantity,
            'edit_status' => false,
            'type' => $this->type,
            'pre_order' => $preOrder
//            'quantity_in_stock_available' => $this->quantity - $this->minimum_quantity < 0 ? 0 : $this->quantity - $this->minimum_quantity,

        ];
    }


    public static function customCollection($resource, $data): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        //you can add as many params as you want.
        self::$data = $data;
        return parent::collection($resource);
    }
}
