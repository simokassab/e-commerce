<?php

namespace App\Http\Resources\Setting;

use App\Models\Settings\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $title= Setting::$titlesArray[array_search($this->title,Setting::$titlesArray)] ?? $this->title;

        return [
            'key' =>$this->id,
            'name' => ucwords(str_replace("_"," ",$title)),
            'type' =>Setting::$titlesTypes[array_search($this->title,Setting::$titlesArray)],
            'options' =>Setting::$titlesOptions[array_search($this->title,Setting::$titlesArray)],
            'value' => $this->value==null ? [] : (int)$this->value,
        ];
    }
}
