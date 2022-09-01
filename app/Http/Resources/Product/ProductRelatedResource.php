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
    private static $relatedProducts;
    private static $relatedProductsImages;

    public function toArray($request)
    {
        $relatedProducts = self::$relatedProducts;
        $relatedProductsImages = self::$relatedProductsImages;

        $product = $relatedProducts->where('id', $this->child_product_id)->first();
        $images =  $relatedProductsImages->where('product_id', $this->child_product_id);
        dd($this);
        return [
            'id' => $this->child_product_id,
            'child_quantity' => $this->child_quantity,
            'name' => $this->getTranslations('name'),
            'name_original' => $product->getTranslations('name'),
            'images' => ProductImagesResource::collection($images) ?? []
        ];
    }

    public static function customCollection($collection, $relatedProducts, $relatedProductsImages)
    {

        self::$relatedProducts = $relatedProducts;
        self::$relatedProductsImages = $relatedProductsImages;

        return parent::collection($collection);
    }
}
