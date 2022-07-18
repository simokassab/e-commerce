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
}




