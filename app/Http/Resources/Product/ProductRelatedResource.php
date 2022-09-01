<?php

namespace App\Http\Resources\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductRelatedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $productRelated = $this;
        $productRelatedIds = $productRelated->pluck('child_product_id');
        $productsRelatedNames = Product::find($productRelatedIds->toArray())->toArray();
        $productRelatedImages=ProductImage::whereIn('product_id',$productRelatedIds->toArray())->get();
        foreach ($productRelated as $key => $product) {
            $name = $productsRelatedNames[$key]['name'];
        }
        return [
            'id' => $this->child_product_id,
            'child_quantity' => $this->child_quantity,
            'name' => $this->getTranslations('name'),
            'name_original' => $name,
            'images' => ProductImagesResource::collection($productRelatedImages) ?? []
        ];
    }
}
