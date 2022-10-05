<?php

namespace App\Http\Resources\Field;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResourceEntity extends JsonResource
{
    private static mixed $selectedFieldValuesOfMultiSelect;

    public static function customerCollection($collection)
    {
        self::$selectedFieldValuesOfMultiSelect = $collection;
        return parent::collection($collection );
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $field = $this->field;
        $value = $this->value;
        if ($field->type == 'checkbox') {
            $value = (bool)$this->value ?? false;
        }
        if ($field->type == 'select') {
            $value = (int)$this->field_value_id ?? null;
        }
        if($field->type == 'multi-select'){
        $fieldValueIds = self::$selectedFieldValuesOfMultiSelect->where('field_id',$field->id)->pluck('id');
        $value = $fieldValueIds->toArray();
        }

        return [
            'id' => $this->id,
            'field_id' => $this->field_id,
            'value' => $value,
            'type' => $field->type

        ];
    }
}
