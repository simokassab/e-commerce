<?php

namespace App\Http\Resources;

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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'node' => new $this($this->parent),
        //    'children' => self::collection($this->whenLoaded('children')),

        ];
    }
}
