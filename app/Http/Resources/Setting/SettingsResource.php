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

        // $integerField =((int)($this->title));
        // if($this->value!=null){

        // if(gettype($this->value)=='string'){
        //         $value = $this->value;
        //     }
        //     else{
        //         $value = [];
        //     }
        // }
        // elseif(gettype($this->value)=='integer'){
        //     $value = (int)$this->value;
        // }

        // else{
        //     $value=$this->value;
        // }

        return [
            'key' =>$this->id,
            'title'=>$title,
            'name' => ucwords(str_replace("_"," ",$title)),
            'type' =>Setting::$titlesTypes[array_search($this->title,Setting::$titlesArray)],
            'options' =>Setting::$titlesOptions[array_search($this->title,Setting::$titlesArray)],
            'value' => (int)$this->value
        ];
    }
}
