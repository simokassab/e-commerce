<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Brand\SelectBrandResource;
use App\Http\Resources\Brand\SingleBrandResource;
use App\Http\Resources\Category\SelectCategoryResource;
use App\Http\Resources\Category\SingleCategoryResource;
use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Field\SelectFieldResource;
use App\Http\Resources\Field\SingleFieldResource;
use App\Http\Resources\Label\SelectLabelResource;
use App\Http\Resources\Label\SingleLableResource;
use App\Http\Resources\Price\SelectPriceResource;
use App\Http\Resources\Tag\TagResource;
use App\Http\Resources\Tax\SelectTaxResource;
use App\Http\Resources\Tax\SingleTaxResource;
use App\Http\Resources\Unit\SelectUnitResource;
use App\Http\Resources\Unit\SingleUnitResource;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductField;
use App\Services\Category\CategoryService;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $categoriesForNested = $this->whenLoaded('category');
        $nestedCategories = CategoryService::getAllCategoriesNested($categoriesForNested);

        $childrenIds = Product::where('parent_product_id',$request->input('id'))->pluck('id')->toArray();
        dd($childrenIds);                            
        // $productAttributes = Product:
        // $productAttributes = ProductField::whereHas('f',$childrenIds);

        // dd($childrenIds);/

        return [
            'id' => (int)$this->id, 
            'name' => $this->getTranslations('name'),
            'slug' => $this->slug,
            'category' => $this->whenLoaded('defaultCategory') ? new SelectCategoryResource($this->whenLoaded('defaultCategory')) : [],
            'code' => $this->code,
            'sku' => $this->sku,
            'type' => $this->type,
            'unit' => $this->whenLoaded('unit') ? new SelectUnitResource($this->whenLoaded('unit')) : [],
            'quantity' => (int)$this->quantity ?? 0,
            'reserved_quantity' => (int)$this->reserved_quantity ?? 0,
            'minimum_quantity' => (int)$this->minimum_quantity ?? 0,
            'summary' => $this->getTranslations('summary') ?? [],
            'specification' => $this->getTranslations('specification')  ?? [],
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'image_path' => $this->image ?? 'default_image',
            'brand' => $this->whenLoaded('brand') ? new SelectBrandResource($this->whenLoaded('brand')) :[],
            'tax' => $this->whenLoaded('tax') ? new SelectTaxResource($this->whenLoaded('tax')) : [],
            'meta_title' => $this->getTranslations('meta_title')  ?? [],
            'meta_description' => $this->getTranslations('meta_description')  ?? [],
            'meta_keyword' => $this->getTranslations('meta_keyword')  ?? [],
            'description' => $this->getTranslations('description')  ?? [],
            'barcode' => $this->barcode ?? null,
            'height' => (int)$this->height ?? 0,
            'width' => (int)$this->width ?? 0,
            'length' => (int)$this->length ?? 0,
            'weight' => (int)$this->weight ?? 0,
            'is_disabled' => (bool)$this->is_disabled,
            'sort' => (int)$this->sort,
            'parent_product_id' => (int)$this->parent_product_id ?? null,
            'is_default_child' => (bool)$this->is_default_child,
            'products_statuses_id' => (int)$this->products_statuses_id,
            'is_show_related_product' => (bool)$this->is_show_related_product,
            'website_status' => $this->website_status,
            'pre_order' => (int)$this->pre_order ?? 0,
            'prices' => ProductPriceResoruce::collection($this->whenLoaded('price')->load('prices.currency')) ?? [],
            'fields' => SingleFieldResource::collection($this->whenLoaded('field'))->where('is_attribute',0) ?? [],
            'attributes' => SingleFieldResource::collection($this->whenLoaded('field'))->where('is_attribute',1) ?? [],
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'labels' => SelectLabelResource::collection($this->whenLoaded('labels')),
            'categories' => $nestedCategories,
            'related_products' => $this->whenLoaded('productRelatedChildren') ? $this->whenLoaded('productRelatedChildren') : [],
            'variations' => $this->whenLoaded('children') ? $this->whenLoaded('children') : [],
            'images' => ProductImagesResource::collection($this->whenLoaded('images')) ?? [],
        ];
    }
}
