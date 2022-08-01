<?php

namespace App\Http\Resources\Price;

use Illuminate\Http\Resources\Json\JsonResource;

class SelectPriceResource extends JsonResource
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
            'price_id' => $this->id,
            'name' => $this->name,
            'currency' => ($this->whenLoaded('currency')->code .' - '.$this->whenLoaded('currency')->symbol)  ?? '-',
        ];
    }
}
