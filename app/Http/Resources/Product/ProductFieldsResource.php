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

        // foreach ($productFields->toArray() as $key => $productField) {
        //     if(!is_null($productField[$key]['field_value_id']) && is_null($productField[$key]['value'])){
        //         $value= (int)$productField[$key]['field_value_id'];
        //     }
        //     else{
        //         $value=$productField[$key]['value'];
        //     }

        // }
        $value =  $productFields->map(function ($value)  {

            if (!is_null($this->field_value_id) && is_null($this->value)) {
                $value = (int)$this->field_value_id;
            } else {
                $value = $this->value;
            }
            return $value;
        })->reject(fn($value,$key) => dd($this) );

        // $currentObject->id = $this->id
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
