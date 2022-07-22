<?php

namespace App\Http\Resources\Setting;

use App\Models\Settings\Setting;
use App\Services\Setting\SettingService;
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
        dd(SettingService::getSetting());
        $title= Setting::$titlesArray[array_search($this->title,Setting::$titlesArray)] ?? $this->title;
        dd(Setting::getAttributeType());
        $value=0;
        switch ($this->type) {
            case 'number':
                $value = (int)$this->value ?? 0;
                break;
            case 'checkbox':
                $value = (bool)$this->value ?? false;
                break;
            case 'multi-select':
                $value = $this->value ?? [];
                break;
            default:
                $value = $this->value ?? "";
                break;
        }

        return [
            'key' =>$this->id,
            'title'=>$title,
            'name' => ucwords(str_replace("_"," ",$title)),
            'type' => $this->type,
            'options' =>Setting::$titlesOptions[array_search($this->title,Setting::$titlesArray)],
            'value' => $value
        ];
    }
}
