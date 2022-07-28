<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Brand\BrandResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\CategoryResource;

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


        $data[0]['name'] = $this->whenLoaded('defaultCategory')->name;
        $data[0]['isMain'] = true;
        $categories = [];
        $categories = $this->whenLoaded('category')->map(
            function ($category) {
                $categoriesArray = [];
                $categoriesArray['name'] = $category->name;
                $categoriesArray['isMain'] = false;

                return $categoriesArray;
            }
        );


        $categories = array_merge($data,$categories->toArray());

        $tags = $this->whenLoaded('tags')->map(
            function ($tag) {
                $tagsArray = [];
                $tagsArray['name'] = $tag->name;
                return $tagsArray;
            }
        );
        return [

            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'image' => $this->image,
            'status' => $this->status,
            'categories' => $categories ?? "-",
            'tags' => count($tags) != 0 ? $tags : '-',
            'brands' =>  $this->whenLoaded('brand') ? $this->whenLoaded('brand')->name : '-',
        ];
    }
}
