<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceResoruce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => (int)$this->product_id,
            'price_id' => (int)$this->price_id,
            'price' => (float)$this->price,
            'discounted_price' => (float)$this->discounted_price,
            'currency' => ($this->whenLoaded('currency')->code.' - '.$this->whenLoaded('currency')->symbol)  ?? '-',
        ];
    }
}
