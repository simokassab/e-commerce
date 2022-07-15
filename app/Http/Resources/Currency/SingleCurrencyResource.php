<?php

namespace App\Http\Resources\Currency;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleCurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $currency_history=$this->whenLoaded('currencyHistory');
        return [
            'id' => $this->id,
            'name'=>$this->name,
            'title'=>$this->code . ' - ' . $this->symbol,
            'code' => $this->code,
            'symbol'=>$this->symbol,
            'rate'=>$this->rate,
            'is_default'=>$this->is_default,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'sort'=>$this->sort,
            'history' => CurrencyHistoryResource::collection($currency_history),
        ];
    }
}
