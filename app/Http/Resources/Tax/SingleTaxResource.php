<?php

namespace App\Http\Resources\Tax;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleTaxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $taxComponent=$this->whenLoaded('taxComponent');

        $languages = Language::all()->pluck('code');

        $translatable = [];

        foreach ($languages as $language){
            $nameTranslatable[$language] = $this->getTranslation('name',$language);
        }

        return[
            'id' => $this->id,
            'name' => $nameTranslatable,
            'is_complex' => (boolean)$this->is_complex,
            'percentage' => $this->percentage,
            'complex_behavior' => $this->is_complex,
            'tax_component' => TaxComponentResource::collection($taxComponent)
        ];
    }
}
