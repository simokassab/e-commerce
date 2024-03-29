<?php

namespace App\Http\Resources\Currency;

use App\Models\Language\Language;
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
        $currencyHistory = $this->whenLoaded('currencyHistory');

        return [
            'id' => $this->id,
            'image' => $this->image && !empty($this->image) ?  getAssetsLink('storage/' . $this->image) : 'default_image',
            'name' => $this->getTranslations('name'),
            'title' => $this->code . ' - ' . $this->symbol,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'rate' => $this->rate,
            'is_default' => (bool)$this->is_default,
            'sort' => $this->sort,
            'history' => CurrencyHistoryResource::collection($currencyHistory),
        ];
    }
}
