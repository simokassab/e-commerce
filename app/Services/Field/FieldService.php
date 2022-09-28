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
        foreach ($field->fieldValue as $item) {
            if($item->product()->exists() || $item->category()->exists() || $item->brand()->exists() ){
                throw new Exception('The field Value is already in used can\'t delete it!');
            }
        }
        if(!$fieldValue->exists()){
            return ;
        }
        $fieldValueId= $fieldValue->pluck('id');
        FieldValue::destroy($fieldValueId);

    }

    public static function addFieldValuesToField(array $fieldValues, Field $field){
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

}





