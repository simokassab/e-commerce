<?php

namespace App\Services\Brand;

use App\Models\Brand\Brand;
use App\Models\Brand\BrandField;
use App\Models\Brand\BrandLabel;

class BrandsService {

    public static function deleteRelatedBrandFieldsAndLabels(Brand $brand){
        if(!BrandField::where('brand_id',$brand->id)->delete() || BrandLabel::where('brand_id',$brand->id)->delete()){
            throw new \Exception('delete brand fields and labels failed');
        }


    }
}




