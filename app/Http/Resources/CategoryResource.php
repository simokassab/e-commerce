<?php

namespace App\Http\Resources;

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

        $data=$this->whenLoaded('parent','children','label','fields','fieldValue','tags','discount','brand','productCategory');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'image' => $this->image,
            'icon' => $this->icon,
            'parent_id' => $this->parent_id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'keyword' => $this->keyword,
            'sort' => $this->sort,
            'is_disabled' => $this->is_disabled,
            'parent' => new CategoryResource($this->whenLoaded('parent')),
            'children' => self::collection( $this->whenLoaded('children')),
            'labels' => new CategoryResource($this->whenLoaded('label')),
            'fields' => new CategoryResource($this->whenLoaded('fields')),
            'fieldsValues' => new CategoryResource($this->whenLoaded('fieldValue')),
            'tags' => new CategoryResource($this->whenLoaded('tags')),
            'discounts' => new CategoryResource($this->whenLoaded('discount')),
            'brands' => new CategoryResource($this->whenLoaded('brand')),
            'products' => new CategoryResource($this->whenLoaded('productCategory')),

             ];
    }
}
