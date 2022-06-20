<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RolesResource;
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $role= $this->whenLoaded('roles');
        return[
        'id' => $this->id,
        'username' => $this->username,
        'email' => $this->email,
        'first_name' => $this->first_name,
        'is_confirmed' => $this->is_confirmed,
        'is_disabled' => $this->is_disabled,
        'role' => RolesResource::collection( $role),
        ];
    }
}