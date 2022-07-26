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
            'parent' => $this->whenLoaded('parent')->name ?? "",
            'children' => self::collection( $this->whenLoaded('children')),
            'labels' => LabelsResource::collection($this->whenLoaded('label')),
            'fields' => FieldsResource::collection($this->whenLoaded('fields')),
            'fieldsValues' => FieldsValueResource::collection($this->whenLoaded('fieldValue')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            // 'discounts' => new discount($this->whenLoaded('discount')),
            // 'brands' => new CategoryResource($this->whenLoaded('brand')),
            // 'products' => new CategoryResource($this->whenLoaded('products')),

             ];
    }
}
