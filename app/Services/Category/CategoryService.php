<?php

namespace App\Services\Category;

use App\Models\Category\CategoriesFields;
use App\Models\Category\CategoriesLabels;
use App\Models\Category\Category;

class CategoryService {

    /**
     * @throws \Exception
     */
    public static function deleteRelatedCategoryFieldsAndLabels(Category $category){
        $deletedFields = true;
        $deletedLabels = true;

        if($category->fields()->exists())
            $deletedFields= CategoriesFields::where('category_id',$category->id)->delete();

        if($category->label()->exists())
            $deletedLabels =  CategoriesLabels::where('category_id',$category->id)->delete();

        if(!( $deletedFields || $deletedLabels)) throw new \Exception('delete category fields and labels failed');


    }

    public static function addFieldsToCategory(Category $category, array $fields)
    {
        $tobeSavedArray = [];
        foreach ($fields as $key => $field) {

            if (gettype($field) == 'string') {
                $field = (array)json_decode($field);
            }

            if ($field["type"] == 'select') {
                if (gettype($field["value"]) == 'array') {
                    $tobeSavedArray[$key]["field_value_id"] = $field["value"][0];
                } elseif (gettype($field["value"]) == 'integer') {
                    $tobeSavedArray[$key]["field_value_id"] = $field["value"];
                }
                $tobeSavedArray[$key]["value"] = null;

            } elseif ($field["type"] == 'text') {
                if (gettype($field["value"]) == 'array') {
                    $tobeSavedArray[$key]["value"] = json_encode($field['value']);
                }
                if (gettype($field) == 'string') {
                    $tobeSavedArray[$key]["value"] = ($field['value']);
                }
                $tobeSavedArray[$key]["field_value_id"] = null;

            } else {
                $tobeSavedArray[$key]["value"] = $field['value'];
                $tobeSavedArray[$key]["field_value_id"] = null;
            }
            $tobeSavedArray[$key]["category_id"] = $category->id;
            $tobeSavedArray[$key]["field_id"] = $field['field_id'];

        }
        return CategoriesFields::insert($tobeSavedArray);
    }

    public static function addLabelsToCategory(Category $category, array $labels){
        $labelsArray=[];
        if(count($labels) <= 0){
            return true;
        }
        foreach ($labels as $key => $label){
            $labelsArray[$key]["category_id"] = $category->id;
            $labelsArray[$key]["label_id"] = $label;
        }
        return CategoriesLabels::insert($labelsArray);
    }

    public static function loopOverMultiDimentionArray(array $arraysOfNestedCategories){

        $array = [];
        $mergedArrays=[];
        foreach ($arraysOfNestedCategories as $key => $arrayOfNestedCategory){
            $array2 = [];
            $tempArray = [];
            $tempArray[] = $arrayOfNestedCategory['label'];
            $tempArray[] = $arrayOfNestedCategory['checked'];
            $array[] = $tempArray;

            if(!is_null($arrayOfNestedCategory['nodes']) && count($arrayOfNestedCategory['nodes']) > 0){
                $array2 = self::loopOverMultiDimentionArray($arrayOfNestedCategory['nodes']);
                $array = array_merge($array,$array2);

            }

            $mergedArrays = array_merge($array,$array2);

        }
        return ($mergedArrays);


    }


}





