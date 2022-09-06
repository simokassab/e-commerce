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

    public function toArray($request)
    {
        $productFields = self::$productFields->toArray();
        // dd($productFields);
        // foreach ($productFields->toArray() as $key => $productField) {
        //     if(!is_null($productField['field_value_id']) && is_null($productField['value'])){
        //         $productsFieldsArray[$key]['value']= (int)$productField['field_value_id'];
        //     }
        // }

        if(!is_null($productFields[0]['field_value_id']) && is_null($productFields[0]['value'])){
            $value= (int)$productFields[0]['field_value_id'];
        }
        else{
            $value=$productFields[0]['value'];
        }

        return [
            'id' => (int)$productFields[0]['id'],
            'field_id' => $productFields[0]['field_id'],
            'value' => $value
        ];
    }

    public static function customCollection($productFields)
    {

        self::$productFields = $productFields;

        return parent::collection($productFields);
    }
}
