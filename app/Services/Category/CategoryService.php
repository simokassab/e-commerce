<?php

namespace App\Services\Category;

use App\Models\Category\CategoriesFields;
use App\Models\Category\CategoriesLabels;
use App\Models\Category\Category;

class CategoryService {

    public static function deleteRelatedCategoryFieldsAndLabels(Category $category){
        if(!CategoriesFields::where('category_id',$category->id)->delete() || CategoriesLabels::where('category_id',$category->id)->delete()){
            return;
        }
    }
    }





