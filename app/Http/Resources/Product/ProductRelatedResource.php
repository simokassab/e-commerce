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
    private static $relatedProductsPrices;

    public function toArray($request)
    {
        $relatedProducts = self::$relatedProducts;
        $relatedProductsImages = self::$relatedProductsImages;
        $relatedProductsPrices= self::$relatedProductsPrices;

        $product = $relatedProducts->where('id', $this->child_product_id)->first();
        $images =  $relatedProductsImages->where('product_id', $this->child_product_id);
        $prices = $relatedProductsPrices->where('product_id', $this->child_product_id);

        return [
            'id' => $this->child_product_id,
            'child_quantity' => $this->child_quantity,
            'name' => $this->getTranslations('name'),
            'name_original' => $product->getTranslations('name'),
            'images' => ProductImagesResource::collection($images) ?? [],
            'prices' => ($prices),
        ];
    }

    public static function customCollection($collection, $relatedProducts, $relatedProductsImages,$relatedProductsPrices)
    {

        self::$relatedProducts = $relatedProducts;
        self::$relatedProductsImages = $relatedProductsImages;
        self::$relatedProductsPrices = $relatedProductsPrices;
        return parent::collection($collection);
    }
}
