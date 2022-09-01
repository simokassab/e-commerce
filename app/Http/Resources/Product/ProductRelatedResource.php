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

        dd($relatedProducts);

        $productRelatedId = ($this)->child_product_id;
        $productsRelatedNames = Product::find($productRelatedId)->toArray();
        $productRelatedImages=ProductImage::where('product_id',$productRelatedId)->get();


        // $name="";
        // foreach ($productRelatedId as $key => $product) {
        //     $name = $productsRelatedNames[$key]['name'];
        // }
        return [
            'id' => $this->child_product_id,
            'child_quantity' => $this->child_quantity,
            'name' => $this->getTranslations('name'),
            // 'name_original' => $name,
            // 'images' => ProductImagesResource::collection($productRelatedImages) ?? []
        ];
    }

    public static function customCollection($collection,$relatedProducts,$relatedProductsImages ){

        self::$relatedProducts = $relatedProducts;
        self::$relatedProductsImages = $relatedProductsImages;

        parent::collection($collection);
    }
}
