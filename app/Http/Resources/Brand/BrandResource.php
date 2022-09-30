<?php

namespace App\Http\Resources\Brand;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'name' => $this->name,
            'code' => $this->code,

        ];
    }
}
