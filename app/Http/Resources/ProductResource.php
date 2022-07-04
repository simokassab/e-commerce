<?php

namespace App\Http\Resources;

use App\Models\Product\Product;
use Illuminate\Http\Resources\Json\JsonResource;

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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'sku' => $this->sku,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'reserved_quantity' => $this->reserved_quantity,
            'minimum_quantity' => $this->minimum_quantity,
            'summary' => $this->summary,
            'specification' => $this->specification,
            'image' => $this->image,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keyword' => $this->meta_keyword,
            'description' => $this->description,
            'status' => $this->status,
            'barcode' => $this->barcode,
            'height' => $this->height,
            'width' => $this->width,
            'length' => $this->length,
            'weight' => $this->weight,
            'is_disabled' => $this->is_disabled,
            'sort' => $this->sort,
            'is_default_child' => $this->is_default_child,
            'parent' =>$this->whenLoaded('parent')->name ?? "",
            'category_id' => new CategoryResource($this->whenLoaded('defaultCategory')),
            // 'unit_id' =>  new UnitResource($this->whenLoaded('unit')),
            // 'tax_id' => new TaxResource($this->whenLoaded('tax')),
            // 'brand_id' =>  new BrandResource($this->whenLoaded('brand')),
            // 'products_statuses_id' =>  ProductResource($this->whenLoaded('')),
        ];
    }
}
