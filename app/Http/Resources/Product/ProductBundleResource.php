<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Price\PriceBundleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class ProductBundleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $settingTitle = Cache::get('settings')->where('title','website_pricing')->pluck('value','title')->toArray();
        
        return [
          'id' => $this->id,
          'name' => $this->getTranslation('name','en'),
          'image' => $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
          'prices' =>  arrayToObject(PriceBundleResource::collection($this->whenLoaded('price'))),
          'default_price' => PriceBundleResource::collection($this->whenLoaded('price'))
        ];
    }
}
