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
//        $role= $this->whenLoaded('roles');
//        $role = gettype($role)  == 'object' && sizeof($role->all()) > 0 ? $role->all()[0]->name : '';
        return[
            'id' => $this->id."",
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'role_name' => $this->roles[0]->name ?? '-',
        ];
    }
}
