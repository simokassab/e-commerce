<?php

namespace App\Http\Resources\Field;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResourceEntity extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $value = $this->value;
        if($this->field->type == 'checkbox'){
            $value = (bool)$this->value;
        }
        return [
            'id' => $this->id,
            'field_id' => $this->field_id,
            'value' =>$value,
            'type' => $this->field->type

        ];
    }
}
