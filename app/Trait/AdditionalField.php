<?php

namespace App\Trait;

use App\Models\Brand\BrandField;
use App\Models\Product\Product;
use Exception;

trait AdditionalField
{
    protected $fieldKey;
    protected $fieldClass;
    protected array $fieldDBColumns;

    /**
     * @throws Exception
     */
    public function storeUpdateFields(array $newFields)
    {

        if ($this->fieldKey == null || $this->fieldClass == null || $this->fieldDBColumns == null) {
            throw new Exception('Missing fieldClass or fieldKey or fieldDBColumns!');
        }

        $oldFields = $this->fieldValue;
        if (count($oldFields) != 0) {
            $newFieldsIds = collect($newFields)->pluck('id');
            $oldFieldsIds = $oldFields->whereNotIn('id', $newFieldsIds)->pluck('id');
            $this->fieldClass::destroy($oldFieldsIds);
        }

        $tobeSavedArray = [];
        foreach ($newFields as $key => $field) {
            $tobeSavedArray[$key]["field_value_id"] = null;

            if ($field["type"] == 'select') {
                $tobeSavedArray[$key]["value"] = null;
                $tobeSavedArray[$key]["field_value_id"] = ($field["value"]);
            } else if ($field["type"] == 'text' || $field["type"] == 'textarea') {
                $tobeSavedArray[$key]["value"] = json_encode($field['value']);
            } else if ($field["type"] == 'checkbox') {
                $tobeSavedArray[$key]["value"] = boolval($field['value']);
            } else if ($field["type"] == 'date') {
                $tobeSavedArray[$key]["value"] = date($field['value']);
            }

            if (self::class === Product::class) {
                $tobeSavedArray[$key]["is_used_for_variations"] = $field['is_used_for_variations'];
            }

            $tobeSavedArray[$key][$this->fieldKey] = $this->id;
            $tobeSavedArray[$key]["field_id"] = $field['field_id'];
            $tobeSavedArray[$key]["id"] = $field['id'];
        }

        return call_user_func($this->fieldClass . '::query')->upsert($tobeSavedArray, ['id'], $this->fieldDBColumns);
    }


}
