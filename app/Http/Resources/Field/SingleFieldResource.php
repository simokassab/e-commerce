<?php

namespace App\Http\Resources\Field;

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
        $fields_values=$this->whenLoaded('fieldValue');

        return [
            'id' =>$this->id,
            'title'=> $this->title,
            'type'=> $this->type,
            'entity'=> $this->entity,
            'is_required'=> $this->is_required,
            'fields_values' => FieldsValueResource::collection($fields_values)
        ];
    }
}
