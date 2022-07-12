<?php

namespace App\Http\Resources\Language;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            // 'is_default' => $this->is_default,
            // 'is_disabled' => $this->is_disabled,
            'image' => $this->image,
            // 'sort' => $this->sort

        ];
    }
}