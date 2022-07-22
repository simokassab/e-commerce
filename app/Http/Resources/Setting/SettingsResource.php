<?php

namespace App\Http\Resources\Setting;

use App\Models\Settings\Setting;
use App\Services\Setting\SettingService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

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
        $titlesArray = [];
        $typesArray = [];
        $valuesArray = [];

         Cache::get('settings')->map(function ($setting) use (&$titlesArray,&$typesArray,&$valuesArray) {
            $titlesArray[] = $setting->title;
            $typesArray[] = $setting->type;
            $valuesArray[] = $setting->value;
             });

        $title= $titlesArray[array_search($this->title,$titlesArray)];
        $type= $typesArray[array_search($this->title,$titlesArray)];
        $value= $valuesArray[array_search($this->title,$titlesArray)];

        switch ($this->type) {
            case 'number':
                $value = (int)$value ?? 0;
                break;
            case 'checkbox':
                $value = (bool)$value ?? false;
                break;
            case 'multi-select':
                $value = $value ?? [];
                break;
            default:
                $value = $value ?? "";
                break;
        }

        return [
            'key' => $this->id,
            'title'=> $title,
            'name' => ucwords(str_replace("_"," ",$title)),
            'type' => $type,
            'options' => Setting::$titlesOptions[array_search($this->title,$titlesArray)],
            'value' => $value
        ];
    }
}
