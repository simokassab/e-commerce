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
        $taxComponent = $this->whenLoaded('taxComponents')->pluck('component_tax_id');
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'is_complex' => (bool)$this->is_complex,
            'percentage' => $this->percentage,
            'complex_behavior' => $this->complex_behavior,
            'components' => $taxComponent,
        ];
    }
}
