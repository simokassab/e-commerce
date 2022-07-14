<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Field\FieldsValueResource;
use App\Http\Resources\Label\LabelsResource;
use App\Http\Resources\Tag\TagResource;
use App\Models\Discount\Discount;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleCategoryResource extends JsonResource
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
            'image'=> 'storage/'.$this->image ?  asset('storage/'.$this->image) : 'default_image' ,
            'icon'=> $this->icon ?  asset('storage/'.$this->icon) : 'default_image' ,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keyword' => $this->meta_keyword,
            'meta_keyword' => $this->description,
            'sort' => $this->sort,
            'is_disabled' => $this->is_disabled,
            'parent' => new CategoryResource($this->whenLoaded('parent')),
            'children' => self::collection( $this->whenLoaded('children')),
            'labels' => LabelsResource::collection($this->whenLoaded('label')),
            'fields' => FieldsResource::collection($this->whenLoaded('fields')),
            'fieldsValues' => FieldsValueResource::collection($this->whenLoaded('fieldValue')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'discounts' => new Discount($this->whenLoaded('discount')),
            'brands' => new CategoryResource($this->whenLoaded('brands')),
            'products' => new CategoryResource($this->whenLoaded('products')),

        ];
    }
}
