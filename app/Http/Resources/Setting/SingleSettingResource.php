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

        $languages = Language::all()->pluck('code');

        $translatable = [];

        foreach ($languages as $language){
            $translatable[$language] = $this->getTranslation('title',$language);
        }


        return [
            'id' => $this->id,
            'title' => $translatable,
            'value' => $this->value,
            'is_developer' => $this->is_developer
        ];
    }
}
