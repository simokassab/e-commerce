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


        $arrayForVirtualInfo['id']  =$this->id;
        $arrayForVirtualInfo['name']  =$this->name ?? '-';
        $arrayForVirtualInfo['currency']  =($this->whenLoaded('currency')->code .' - '.$this->whenLoaded('currency')->symbol)  ?? '-';
        $arrayForVirtualInfo['original_price_id'] = ($this->whenLoaded('originalPrice')->name) ?? '-';
        $arrayForVirtualInfo['original_percent'] = ($this->percentage) ?? '-';

        return $arrayForVirtualInfo;

    }
}
