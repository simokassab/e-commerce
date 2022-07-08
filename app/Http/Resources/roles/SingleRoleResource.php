<?php

namespace App\Http\Resources\roles;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parentId = $this->parent ? $this->parent->id : '-';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_role' => $parentId,
            'children' => self::collection($this->whenLoaded('children')),

        ];
    }
}
