<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Field\FieldResourceEntity;
use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Field\FieldsValueResource;
use App\Http\Resources\Label\LabelsResource;
use App\Http\Resources\Tag\TagResource;
use App\Models\Discount\Discount;
use App\Models\Language\Language;
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

        $labels = $this->whenLoaded('label') ? $this->whenLoaded('label')->pluck('id') : [];
        $fieldsValues = $this->whenLoaded('fieldValue');

        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'code' => $this->code,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'icon'=> $this->icon && !empty($this->icon) ?  getAssetsLink('storage/'.$this->icon): 'default_icon' ,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'meta_title' =>  $this->getTranslations('meta_title'),
            'meta_description' => $this->getTranslations('meta_description'),
            'meta_keyword' => $this->getTranslations('meta_keyword'),
            'description' => $this->getTranslations('description'),
            'labels' => $labels,
            'fields' => FieldResourceEntity::customerCollection($fieldsValues),
            // 'brands' => new CategoryResource($this->whenLoaded('brands')),

        ];
    }
}
