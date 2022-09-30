<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)

{
    $parentName = $this->whenLoaded('parent') ? $this->whenLoaded('parent')->name : '-';
        return [
            'id' => $this->id,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'name' => $this->name,
            'code' => $this->code,
            'icon'=> $this->icon && !empty($this->icon) ?  getAssetsLink('storage/'.$this->icon): 'default_icon' ,
            'parent' => $parentName,
            'slug' => $this->slug,

             ];
    }
}
