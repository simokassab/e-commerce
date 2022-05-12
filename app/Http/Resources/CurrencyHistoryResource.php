<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
USE App\Http\Resources\CurrencyResource;
class CurrencyHistoryResource extends JsonResource
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
            'data' => [
                'id' => $this->id,
                'rate' => $this->rate,
                // 'currency' => new CurrencyResource($this->currency),
                'currency' =>$this->currency,
            ]
        ];
    }
}
