<?php

namespace App\Services\Category;

use App\Models\Category\CategoriesFields;
use App\Models\Category\CategoriesLabels;
use App\Models\Category\Category;

class CategoryService
{

    /**
     * @throws \Exception
     */
    public static function deleteRelatedCategoryLabels(Category $category)
    {
        $deletedLabels = true;

        if ($category->label()->exists())
            $deletedLabels =  CategoriesLabels::where('category_id', $category->id)->delete();

        if (!( $deletedLabels)) throw new \Exception('delete category fields and labels failed');
    }

    public static function addFieldsToCategory(Category $category, array $fields = [])
    {
        $tobeSavedArray = [];
        foreach ($fields as $key => $field) {

            if ($field["type"] == 'select') {
                $tobeSavedArray[$key]["value"] = null;
                if (is_array($field["value"])) {
                    //if multiple elements where sent only take the first element
                    $tobeSavedArray[$key]["field_value_id"] = $field["value"][0];
                } elseif (is_int($field["value"])) {
                    $tobeSavedArray[$key]["field_value_id"] = $field["value"];
                }
            } else {
                $tobeSavedArray[$key]["field_value_id"] = null;
                $tobeSavedArray[$key]["value"] = ($field['value']);
            }
            $tobeSavedArray[$key]["category_id"] = $category->id;
            $tobeSavedArray[$key]["field_id"] = $field['field_id'];
        }
        return CategoriesFields::query()->insert($tobeSavedArray);
    }

    public static function addLabelsToCategory(Category $category, array $labels = [])
    {
        $labels = $labels ? $labels : [];

        $labelsArray = [];
        if (count($labels) <= 0) {
            return true;
        }
        foreach ($labels as $key => $label) {
            $labelsArray[$key]["category_id"] = $category->id;
            $labelsArray[$key]["label_id"] = $label;
        }
        return CategoriesLabels::insert($labelsArray);
    }

    public static function loopOverMultiDimentionArray(array $arraysOfNestedCategories)
    {

        $array = [];
        $mergedArrays = [];
        foreach ($arraysOfNestedCategories as $key => $arrayOfNestedCategory) {
            $array2 = [];
            $tempArray = [];
            $tempArray['label'] = $arrayOfNestedCategory['label'];
            $tempArray['id'] = $arrayOfNestedCategory['id'];
            $tempArray['checked'] = $arrayOfNestedCategory['checked'];
            $array[] = $tempArray;

            if (array_key_exists("nodes", $arrayOfNestedCategory)) {
                if (!is_null($arrayOfNestedCategory['nodes']) && count($arrayOfNestedCategory['nodes']) > 0) {
                    $array2 = self::loopOverMultiDimentionArray($arrayOfNestedCategory['nodes']);
                    $array = array_merge($array, $array2);
                }
            }


            $mergedArrays = array_merge($array, $array2);
        }
        return ($mergedArrays);
    }

    public static function getAllCategoriesNested($categories, $selectedCategoriesIds = [])
    {

        $rootCategories = self::getRootCategories($categories);
        $lastResult = [];
        foreach ($rootCategories as $rootCategory) {
            $result = (object)[];
            $result->id = $rootCategory->id;
            $result->label = $rootCategory->name;
            $result->checked = in_array($rootCategory->id, $selectedCategoriesIds);
            $result->expanded = true;
            $nodes = (array)self::getCategoryChildren($rootCategory, $categories, $selectedCategoriesIds);
            $nodesArray = [];

            if (is_array($nodes) && count($nodes) > 0) {
                foreach ($nodes as $node) {
                    $nodesArray[] = $node;
                }
            }

            $result->nodes = $nodesArray;

            $result = (array)$result;
            $lastResult[] = $result;
        }
        return $lastResult;
    }

    private static function getRootCategories($categories)
    {
        $arrayOfParents = [];
        $arrayOfParentsCodes = [];

        foreach ($categories as $category) {
            if (!is_null($category->parent_id)) {
                continue;
            }
            if (is_null($category->parent_id)) {
                $arrayOfParents[] = $category;
            }
        }

        return ($arrayOfParents);
    }

    private static function getCategoryChildren(int | Category $category, $allCategories, $selectedCategoriesIds = [])
    {

        $categoriesChildren = self::generateChildrenForAllCategories($allCategories);
        $categoryId = (is_numeric($category) ? $category : $category->id);

        return self::drawCategoryChildren($categoryId, $categoriesChildren, true, $allCategories, $selectedCategoriesIds);
    }

    private static function drawCategoryChildren($parentCategoryId, $allCategoryIDs, $isMultiLevel = false, $allCategories, $selectedCategoriesIds = []): array
    {
        //with levels
        $childCategory = array();
        if (empty($allCategoryIDs[$parentCategoryId])) {
            return [];
        }
        foreach ($allCategoryIDs[$parentCategoryId] as $categoryID) {

            $categoryID =  is_numeric($categoryID) ? ($categoryID) : $categoryID->id;

            if ($isMultiLevel) {
                $childCategory[] = [
                    'id' => $allCategories->find($categoryID)->id,
                    'label' => $allCategories->find($categoryID)->name,
                    'checked' => in_array($categoryID, $selectedCategoriesIds),
                    'expanded' => true,
                    'nodes' => self::drawCategoryChildren($categoryID, $allCategoryIDs, $isMultiLevel, $allCategories),
                ];
                //

                //                $childCategory[$categoryID]['nodes'] = self::drawCategoryChildren($categoryID, $allCategoryIDs, $isMultiLevel, $allCategories);
            } else {
                $childCategory[] = $categoryID;
                $childCategory = array_merge($childCategory, self::drawCategoryChildren($categoryID, $allCategoryIDs, $isMultiLevel, $allCategories));
            }
        }
        return $childCategory;
    }

    private static function generateChildrenForAllCategories($allCategories)
    {
        $categoryChildren = [];
        foreach ($allCategories as $currentCategory) {
            $parentId = ($currentCategory->parent_id ?? 0);

            if (!isset($categoryChildren[$parentId])) {
                $categoryChildren[$parentId] = [];
            }
            $categoryChildren[$parentId][] = collect($allCategories)->where('id', $currentCategory->id)->first();
        }


        return $categoryChildren;
    }
}
