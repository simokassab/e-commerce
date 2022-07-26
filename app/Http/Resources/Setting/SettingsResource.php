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
        $idsArray = [];
        $titlesArray = [];
        $typesArray = [];
        $valuesArray = [];

         Cache::get('settings')->map(function ($setting) use (&$idsArray,&$titlesArray,&$typesArray,&$valuesArray) {
            $idsArray[] = $setting->id;
            $titlesArray[] = $setting->title;
            $typesArray[] = $setting->type;
            $valuesArray[] = $setting->value;
             });

        $id= $idsArray[array_search($this->title,$titlesArray)];
        $title= $titlesArray[array_search($this->title,$titlesArray)];
        $type= $typesArray[array_search($this->title,$titlesArray)];
        $value= $valuesArray[array_search($this->title,$titlesArray)];

        switch ($type) {
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
            'key' => $id,
            'title'=> $title,
            'name' => ucwords(str_replace("_"," ",$title)),
            'type' => $type,
            'options' => Setting::$titlesOptions[$title],
            'value' => $value
        ];
    }
}
