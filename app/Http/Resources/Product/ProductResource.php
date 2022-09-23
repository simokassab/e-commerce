<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

use function PHPSTORM_META\map;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $category = $this->whenLoaded('defaultCategory') ? $this->whenLoaded('defaultCategory')->name : "-";

        $tags = $this->whenLoaded('tags')->map(
            function ($tag) {
                $tagsArray = [];
                $tagsArray['name'] = $tag->name;
                return $tagsArray;
            }
        );
        return [

            'id' => $this->id,
            'image' => $this->image && !empty($this->image) ?  getAssetsLink('storage/' . $this->image) : 'default_image',
            'name' => $this->name,
            'sku' => $this->sku,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'website_status' => $this->website_status,
            'categories' => $category,
            'tags' => count($tags) != 0 ? $tags : '-',
            'brands' =>  $this->whenLoaded('brand') ? $this->whenLoaded('brand')->name : '-',
        ];
    }
}
