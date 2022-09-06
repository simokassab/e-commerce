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
        $productFields = self::$productFields;
        $productFieldsArray=self::$productFields->toArray();
        // foreach ($productFields->toArray() as $key => $productField) {
        //     if(!is_null($productField['field_value_id']) && is_null($productField['value'])){
        //         $productsFieldsArray[$key]['value']= (int)$productField['field_value_id'];
        //     }
        // }

      $value=  $productFields->map(function($value) use($productFieldsArray){
            if(!is_null($productFieldsArray['field_value_id']) && is_null($productFieldsArray['value'])){
                $value= (int)$productFieldsArray['field_value_id'];
            }
            else{
                $value=$productFieldsArray->value;
            }
            return $value;
        });



        return [
            'id' => $productFields['id'],
            'field_id' => $productFields['field_id'],
            'value' => $value
        ];
    }

    public static function customCollection($productFields)
    {

        self::$productFields = $productFields;

        return parent::collection($productFields);
    }
}
