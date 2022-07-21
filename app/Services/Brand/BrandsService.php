<?php

namespace App\Services\Brand;

use App\Models\Brand\Brand;
use App\Models\Brand\BrandField;
use App\Models\Brand\BrandLabel;

class BrandsService {

    public static function deleteRelatedBrandFieldsAndLabels(Brand $brand){
        $deletedFields = true;
        $deletedLabels = true;

        if($brand->field()->exists())
            $deletedFields= BrandField::where('brand_id',$brand->id)->delete();

        if($brand->label()->exists())
            $deletedLabels =  BrandLabel::where('brand_id',$brand->id)->delete();

        if(!( $deletedFields || $deletedLabels)){
            throw new \Exception('delete brands fields and labels failed');
        }


    }

    public static function addFieldsToBrands(Brand $brand, array $fields){
        $fieldsArray = $fields;
        $tobeSavedArray = [];
        foreach ($fields as $key => $field){
            if(gettype($field) == 'string' ){
                $field = (array)json_decode($field);
            }
            if(gettype($field) == 'string'){
                $fieldsArray = (array)json_decode($fieldsArray[$key]);
            }
            if($field["type"]=='select' && gettype($field['value']) == 'integer' ){
                $tobeSavedArray[$key]["value"] = null;
                $tobeSavedArray[$key]["field_value_id"] = $field["value"];
            }
            else if($field["type"] != 'select'){
                $tobeSavedArray[$key]["field_value_id"] = null;
                $tobeSavedArray[$key]["value"] = ($field['value']);
            }
            $tobeSavedArray[$key]["brand_id"] = $brand->id;
            $tobeSavedArray[$key]["field_id"] = $field['field_id'];

//            unset($fieldsArray[$key]['type']);
        }
        return BrandField::insert($tobeSavedArray);

    }

    public static function addLabelsToBrands(Brand $brand, array $labels){
        $labelsArray=[];
        foreach ($labels as $label => $value){
            $labelsArray[$label]["brand_id"] = $brand->id;
            $labelsArray[$label]["label_id"] = $value;
        }

        return BrandLabel::insert($labelsArray);
    }

}






