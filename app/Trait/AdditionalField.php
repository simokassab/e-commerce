<?php

namespace App\Trait;

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

        //explode all multi selects to smaller select to save in the database
        $multiSelectFields = collect($newFields)->where('type','multi-select');
        foreach ($multiSelectFields as $selectField){
            foreach ($selectField['value'] as $innerFieldValue){
                $tempArray = [];
                $tempArray['id'] = $selectField['id'];
                $tempArray['field_id'] = $selectField['field_id'];
                $tempArray['type'] = 'select';
                $tempArray['value'] = $innerFieldValue;
                $newFields[] = $tempArray;
            }
        }

        $toBeSavedArray = [];
        foreach ($newFields as $key => $field) {
            //ignore the multi selects since we already have exploded them to smaller selects
            if($field['type'] === 'multi-select'){
                continue;
            }
            $tobeSavedArray[$key]["field_value_id"] = null;

            if ($field["type"] == 'select') {
                $toBeSavedArray[$key]["value"] = null;
                $toBeSavedArray[$key]["field_value_id"] = ($field["value"]);
            }else if ($field["type"] == 'text' || $field["type"] == 'textarea') {
                $toBeSavedArray[$key]["value"] = json_encode($field['value']);
            } else if ($field["type"] == 'checkbox') {
                $toBeSavedArray[$key]["value"] = boolval($field['value']);
            } else if ($field["type"] == 'date') {
                $toBeSavedArray[$key]["value"] = date($field['value']);
            }

            if (self::class === Product::class) {
                $toBeSavedArray[$key]["is_used_for_variations"] = $field['is_used_for_variations'];
            }

            $toBeSavedArray[$key][$this->fieldKey] = $this->id;
            $toBeSavedArray[$key]["field_id"] = $field['field_id'];
            $toBeSavedArray[$key]["id"] = $field['id'];
        }
        return call_user_func($this->fieldClass . '::query')->upsert($toBeSavedArray, ['id'], $this->fieldDBColumns);
    }

    public static function generateValidationRules(array $fields)
    {

        $fieldsRules = [];
        foreach ($fields as $key => $field) {
            if ($field['type'] == 'date') {
                $fieldsRules['fields.*.value'] = 'required | date';
            } elseif ($field['type'] == 'select') {
                $fieldsRules['fields.*.value'] = 'required | integer | exists:fields_values,id';
            } elseif ($field['type'] == 'multi-select') {
                $fieldsRules['fields.*.value'] = 'required | array | exists:fields_values,id';
            } elseif ($field['type'] == 'checkbox') {
                dd('helllo');
                $fieldsRules['fields.*.value'] = 'required | boolean';
            } elseif ($field['type'] == 'text' || $field['type'] == 'textarea') {
                $fieldsRules['fields.*.value'] = 'required | array';
            }
        }
        return $fieldsRules;
    }


}
