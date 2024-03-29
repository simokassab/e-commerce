<?php

namespace App\Http\Resources\Field;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldsValueResource extends JsonResource
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
            'field_id' => $this->field_id,
            'value' => $this->value,

        ];
    }
}
