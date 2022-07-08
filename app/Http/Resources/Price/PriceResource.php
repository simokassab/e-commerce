<?php

namespace App\Http\Resources\Price;

use Illuminate\Http\Resources\Json\JsonResource;
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
        // $arrayForVirtualInfo = [];


        // $arrayForVirtualInfo['id']  =$this->id;
        // $arrayForVirtualInfo['name']  =$this->name ?? '-';
        // $arrayForVirtualInfo['is_virtual']  = $this->is_virtual;
        // $arrayForVirtualInfo['currency']  =($this->whenLoaded('currency')->code .' - '.$this->whenLoaded('currency')->symbol)  ?? '-';
        // $arrayForVirtualInfo['original_price_id'] = ($this->whenLoaded('originalPrice')->name) ?? '-';
        // $arrayForVirtualInfo['original_percent'] = ($this->percentage) ?? '-';

        // return $arrayForVirtualInfo;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_virtual' => $this->is_virtual,
            'currency' => ($this->whenLoaded('currency')->code .' - '.$this->whenLoaded('currency')->symbol)  ?? '-',
            'original_price_id' => ($this->whenLoaded('originalPrice')->name) ?? '-',
            'original_percent' => (round($this->original_percent,config('defaults.default_round_percentage'))) ?? '-',
            'percentage' => (round($this->percentage,config('defaults.default_round_percentage'))) ?? '-',
        ];

    }
}
