<?php

namespace App\Http\Resources\Field;

use App\Http\Controllers\Fields\FieldValueController;
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

         $fields_values=$this->whenLoaded('fieldValue');
        return [
            'id' =>$this->id,
            'title'=> $this->title,
            'type'=> $this->type,
            'entity'=> $this->entity,
            // 'is_required'=> $this->is_required,
             'select_options' => FieldsValueResource::collection($fields_values)
        ];
    }
}
