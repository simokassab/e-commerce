<?php

namespace App\Http\Resources\Field;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fieldValues=$this->whenLoaded('fieldValue');

        return [
            'id' =>$this->id,
            'title'=> $this->getTranslations('title'),
            'type'=> $this->type,
            'entity'=> $this->entity,
            'is_required'=> (bool)$this->is_required,
            'field_values' => SingleFieldEntityResource::collection($fieldValues),
            'is_attribute'=> (bool)$this->is_attribute,
        ];
    }
}
