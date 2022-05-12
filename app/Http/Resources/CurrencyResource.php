<?php

namespace App\Http\Resources;

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
            'name'=>$this->name,
            'code'=>$this->code,
            'symbol'=>$this->symbol,
            'rate'=>$this->rate,
            'is_default'=>$this->is_default,
            'image'=>$this->image,
            'sort'=>$this->sort,
            'history' => CurrencyHistoryResource::collection($this->currencyHistory),

        ];
    }
}
