<?php

namespace App\Http\Resources\Price;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceBundleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
    //    $returnArray=[

    //          'id' => $this->id,
    //          'price'=> $this->price

    //    ];

    //    return $returnArray;

       return [
            'id' => $this->price_id,
            'price'=> $this->price
        ];
    }
}
