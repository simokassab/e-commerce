<?php

namespace App\Services\Category;

use App\Models\Brand\Brand;
use App\Models\Brand\BrandField;
use App\Models\Brand\BrandLabel;
use App\Models\Category\CategoriesFields;
use App\Models\Category\CategoriesLabels;
use App\Models\Category\Category;

class CategoryService {

    public static function deleteRelatedCategoryFieldsAndLabels(Category $category){
        if(!CategoriesFields::where('category_id',$category->id)->delete() || CategoriesLabels::where('category_id',$category->id)->delete()){
            return;
        }
    }

    public static function addFieldsToCategory(Category $category, array $fields){
        $tobeSavedArray = [];
        foreach ($fields as $key => $field){

            if(gettype($field) == 'string' ){
                $field = (array)json_decode($field);
            }
            if($field["type"]=='select'){
                $tobeSavedArray[$key]["value"] = null;
                if(gettype($field["value"]) == 'array'){
                    $tobeSavedArray[$key]["field_value_id"] = $field["value"][0];
                }elseif(gettype($field["value"]) == 'integer'){
                    $tobeSavedArray[$key]["field_value_id"] = $field["value"];
                }
            }
            else if($field["type"] != 'select'){
                $tobeSavedArray[$key]["field_value_id"] = null;
                $tobeSavedArray[$key]["value"] = ($field['value']);
            }
            $tobeSavedArray[$key]["brand_id"] = $category->id;
            $tobeSavedArray[$key]["field_id"] = $field['field_id'];

//            unset($fieldsArray[$key]['type']);
        }
        return CategoriesFields::insert($tobeSavedArray);

    }

    public static function addLabelsToCategory(Category $category, array $labels){
        $labelsArray=[];
        if(count($labels) <= 0){
            return true;
        }
        foreach ($labels as $key => $label){
            $labelsArray[$key]["brand_id"] = $category->id;
            $labelsArray[$key]["label_id"] = $label;
        }

        return CategoriesLabels::insert($labelsArray);
    }

}





