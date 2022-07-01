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
            $arrayForVirtualInfo['parent_class'] = $this->whenLoaded('originalPrice')->name;
            $arrayForVirtualInfo['percentage'] = $this->original_percent;
        }
        $arrayForVirtualInfo['id']  =$this->id;
        $arrayForVirtualInfo['name']  =$this->name;
        $arrayForVirtualInfo['currency']  =$this->whenLoaded('currency')->code .' - '.$this->whenLoaded('currency')->symbol ;


        return $arrayForVirtualInfo;

    }
}
