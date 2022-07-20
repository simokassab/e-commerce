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
        foreach ($fields as $key => $field){
            if($fieldsArray[$key]["type"]=='select' && gettype($field['value']) == 'integer' ){
                $fieldsArray[$key]["value"] = null;
                $fieldsArray[$key]["field_value_id"] = $field["value"];
            }
            else if($fieldsArray[$key]["type"] != 'select'){
                $fieldsArray[$key]["field_value_id"] = null;
                $fieldsArray[$key]["value"] = ($field['value']);
            }
            $fieldsArray[$key]["brand_id"] = $brand->id;

            unset($fieldsArray[$key]['type']);
        }
        return BrandField::insert($fieldsArray);

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






