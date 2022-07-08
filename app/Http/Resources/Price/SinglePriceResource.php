<?php

namespace App\Http\Resources\Price;

use Illuminate\Http\Resources\Json\JsonResource;

class SinglePriceResource extends JsonResource
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
            'name' => $this->name,
            'is_virtual' => (bool)$this->is_virtual,
            'currency' => $this->whenLoaded('currency')->id ,
            'original_price_id' => ($this->whenLoaded('originalPrice')->id) ?? '-',
            'percentage' => (round($this->percentage,config('defaults.default_round_percentage'))) ?? '-',
        ];
    }
}
