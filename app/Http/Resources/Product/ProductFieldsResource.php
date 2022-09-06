<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductFieldsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    private static $productFields;
    private static $allFields;

    public function toArray($request)
    {
        $productFields = self::$productFields;

        $value = $productFields->where('id',$this->id)->first();



        return [
            'id' => $this->id,
            'field_id' => $this->field_id,
            'value' => $value
        ];
    }

    public static function customCollection($productFields)
    {

        self::$productFields = $productFields;

        return parent::collection($productFields);
    }
}
