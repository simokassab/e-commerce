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

        $labels = $this->whenLoaded('label')->pluck('id');
        $fieldsValues = $this->whenLoaded('fieldValue');


        $languages = Language::all()->pluck('code');
        $nameTranslatable = [];
        $metaTitleTranslatable = [];
        $metaDescriptionTranslatable = [];
        $metaKeyWordTranslatable = [];
        $descriptionTranslatable = [];

        foreach ($languages as $language){
            $nameTranslatable[$language] = $this->getTranslation('name',$language);
            $metaTitleTranslatable[$language] = $this->getTranslation('meta_title',$language);
            $metaDescriptionTranslatable[$language] = $this->getTranslation('meta_description',$language);
            $metaKeyWordTranslatable[$language] = $this->getTranslation('meta_keyword',$language);
            $descriptionTranslatable[$language] = $this->getTranslation('description',$language);
        }

        $parentId = $this->whenLoaded('parent') ? $this->whenLoaded('parent')->id : null ;

        return [
            'id' => $this->id,
            'name' => $nameTranslatable,
            'code' => $this->code,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'icon'=> $this->icon && !empty($this->icon) ?  getAssetsLink('storage/'.$this->icon): 'default_icon' ,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'meta_title' => $metaTitleTranslatable,
            'meta_description' => $metaDescriptionTranslatable,
            'meta_keyword' => $metaKeyWordTranslatable,
            'description' => $descriptionTranslatable,
//            'sort' => $this->sort,
//            'is_disabled' => $this->is_disabled,
            'parent_category_id' => $parentId,
//            'children' => self::collection( $this->whenLoaded('children')),
            'labels' => $labels,
            'fields' => FieldResourceEntity::collection($fieldsValues),

//            'tags' => TagResource::collection($this->whenLoaded('tags')),
//            'discounts' => new Discount($this->whenLoaded('discount')),
            'brands' => new CategoryResource($this->whenLoaded('brands')),
//            'products' => new CategoryResource($this->whenLoaded('products')),

        ];
    }
}
