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

    public static function createComponentsForTax($components, $tax){
        $componentsArray=array();
        foreach ($components as $key => $value){
            $componentsArray[$key]["component_tax_id"] = $value;
            $componentsArray[$key]["tax_id"] = $tax->id;
            $componentsArray[$key]["sort"] = $key+1;
        }

        TaxComponent::insert($componentsArray);
    }

}





