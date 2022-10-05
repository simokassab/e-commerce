<?php

namespace App\Http\Resources\Field;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $toBeReturned =  [
            'id' =>$this->id,
            'title'=> $this->title,
            'type'=> $this->type,
            'entity'=> $this->entity,
            'is_required'=> (bool)$this->is_required,
        ];

        if($this->type == 'select' || $this->type == 'multi-select'){
            $fieldValues=$this->whenLoaded('fieldValue');
            $toBeReturned['select_options'] = FieldsValueResource::collection($fieldValues);
        }

        return $toBeReturned;


    }
}
