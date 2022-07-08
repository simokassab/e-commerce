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
        $roles=$this->whenLoaded('roles');
        $parentId = $this->parent ? $this->parent->id : 0;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_role' => $parentId,
        //    'children' => self::collection($this->whenLoaded('children')),

        ];
    }
}
