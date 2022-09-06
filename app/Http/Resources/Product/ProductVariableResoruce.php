<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariableResoruce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    private static $childrenFieldValues;

    public function toArray($request)
    {
        $childrenFieldValues= self::$childrenFieldValues;
        // dd($childrenFieldValues);
        //Product fiedl values
        // fields le hene attributes

        // $fieldValuesIds = $fieldValues->where('product_id',$this->id)->pluck('id');

        // $ProductAttributeValue = $fieldValues->whereIn('field_id',$productAttributesIds);
        $ProductAttribute = $childrenFieldValues->where('proudct_id',$this->id);
        dd($this->id);
        return [
            'id' => (int)$this->id,
            'name' => $this->getTranslations('name'),
            'code' => $this->code,
            'sku' => $this->sku,
            'quantity' => (float)$this->quantity,
            'reserved_quantity' => (float)$this->reserved_quantity,
            'minimum_quantity' => (float)$this->minimum_quantity,
            'description' => $this->getTranslations('description'),
            'specification' => $this->getTranslations('specification'),
            'image' => $this->image && !empty($this->image) ?  getAssetsLink('storage/' . $this->image) : 'default_image',
            'barcode' => $this->barcode,
            'height' => (float)$this->height,
            'width' => (float)$this->height,
            'length' => (float)$this->height,
            'weight' => (float)$this->height,
            'is_default_child' => (bool)$this->is_default_child,
            'products_statuses_id' =>(int)$this->products_statuses_id,
            'attributes' => SelectProductAttributesResource::collection($ProductAttribute )
        ];
    }

    public static function customCollection($collection, $childrenFieldValues)
    {

        self::$childrenFieldValues = $childrenFieldValues;

        return parent::collection($collection);
    }
}
