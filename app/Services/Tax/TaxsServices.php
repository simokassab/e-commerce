<?php

namespace App\Services\Tax;
use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;

class TaxsServices{

    public static function deleteRelatedTaxComponents(Tax $tax){
        $taxComponents = $tax->taxComponents();
        if(!$taxComponents->exists()){
            return ;
        }
        $taxComponentsId= $taxComponents->pluck('id');
        TaxComponent::destroy($taxComponentsId);

    }

}





