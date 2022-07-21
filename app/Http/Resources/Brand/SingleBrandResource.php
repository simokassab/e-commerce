<?php

namespace App\Http\Resources\Brand;

use App\Http\Resources\Field\FieldResourceEntity;
use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Field\FieldsValueResource;
use App\Http\Resources\Label\SingleLableResource;
use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleBrandResource extends JsonResource
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


        return [
            'id' => $this->id,
            'name' => $nameTranslatable,
            'code' => $this->code,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'meta_title' => $metaTitleTranslatable,
            'meta_description' => $metaDescriptionTranslatable,
            'meta_keyword' => $metaKeyWordTranslatable,
            'description' => $descriptionTranslatable,
            'keyword' => $this->keyword,
            'sort' => $this->sort,
            'is_disabled' => $this->is_disabled,
            'labels' => ($labels),
            'fields' => FieldResourceEntity::collection($fieldsValues),


        ];
    }
}
