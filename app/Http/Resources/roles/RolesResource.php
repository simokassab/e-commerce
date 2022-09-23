<?php

namespace App\Http\Resources\roles;

use Illuminate\Http\Resources\Json\JsonResource;

class RolesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parentName = $this->parent ? $this->parent->name : '-';
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_role' => $parentName,

        ];
    }
}
