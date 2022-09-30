<?php

namespace App\Http\Resources\Field;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleFieldEntityResource extends JsonResource
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
            'value' => $this->getTranslations('value'),
        ];
    }
}
