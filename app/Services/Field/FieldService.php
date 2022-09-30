<?php

namespace App\Services\Field;

use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use Exception;

class FieldService{

    /**
     * @throws Exception
     */
    public static function deleteRelatedfieldValues(Field $field){
        $fieldValue = $field->fieldValue();

        if(!$fieldValue->exists()){
            return ;
        }
        $fieldValueId= $fieldValue->pluck('id');
        FieldValue::destroy($fieldValueId);

    }

    public static function addOrUpdateFieldValuesToField(array $fieldValues, Field $field){

        self::deleteNonReusedFieldValues( $fieldValues,  $field);
        $fieldsValuesArray = [];
        foreach ($fieldValues as $key => $value){
            if(!array_key_exists('id',$value)){
                $fieldsValuesArray[$key]['id'] = null;
            }else{
                $fieldsValuesArray[$key]['id'] = $value['id'];
            }
            $fieldsValuesArray[$key]['field_id'] = $field->id;
            $fieldsValuesArray[$key]['value'] = json_encode($value['value']);
        }
        return FieldValue::query()->upsert($fieldsValuesArray,['id'],['field_id','value']);

    }

    public static function deleteNonReusedFieldValues(array $fieldValues, Field $field): void
    {
        $oldFieldValuesIds = $field->fieldValue->pluck('id')->toArray();
        $newFieldValuesIds = (collect($fieldValues)->pluck('id'))->toArray();

        $toBeDeletedValues = array_diff($oldFieldValuesIds,$newFieldValuesIds);

        FieldValue::destroy($toBeDeletedValues);

    }

}





