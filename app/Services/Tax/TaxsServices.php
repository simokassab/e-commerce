<?php

namespace App\Services\Tax;
use App\Models\Tax\Tax;
use App\Models\Category\Category;

class TaxsServices{

    public static function deleteRelatedTaxComponents(Tax $tax){
        $taxComponents = $tax->taxComponents();
        if(!$taxComponents->exists()){
            return ;
        }
        $taxComponentsId= $taxComponents->pluck('id');
        Tax::destroy($taxComponentsId);

    }

}




