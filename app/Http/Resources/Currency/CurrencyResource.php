<?php

namespace App\Http\Resources\Currency;

use App\Models\Currency\Currency;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CurrencyHistoryResource;


class CurrencyResource extends JsonResource
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
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_icon' ,
            'name'=>$this->name,
            'code' => $this->code,
            'symbol'=>$this->symbol,
            'rate'=>$this->rate,

        ];
    }
}
