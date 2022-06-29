<?php

namespace App\Http\Resources;

use App\Models\Price\Price;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Currency\Currency;
use App\Http\Resources\CurrencyResource;
class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $arrayForVirtualInfo = [];

        if($this->is_virtual == 1){
            $arrayForVirtualInfo['original_percent'] = $this->original_percent;
            $arrayForVirtualInfo['original_price'] = new self($this->whenLoaded('originalPrice'));

        }
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'currency' => new CurrencyResource( $this->whenLoaded('currency')),
            'is_virtual' => $this->is_virtual,

        ];


        return array_merge($array,$arrayForVirtualInfo);

    }
}
