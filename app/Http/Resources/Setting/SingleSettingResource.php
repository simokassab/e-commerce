<?php

namespace App\Http\Resources\Setting;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

//        $languages = Language::all()->pluck('code');
//
//        $titleTranslatable = [];
//        $valueTranslatable = [];
//
//        foreach ($languages as $language){
////            $titleTranslatable[$language] = $this->getTranslation('title',$language);
//            $valueTranslatable[$language] = $this->getTranslation('value',$language);
//        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'value' => $this->value,
            'is_developer' => $this->is_developer
        ];
    }
}
