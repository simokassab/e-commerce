<?php

namespace App\Models\Tax;

use App\Models\Price\Price;
use App\Models\Tax\TaxComponent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Brand\Brand;
use Spatie\Translatable\HasTranslations;

class Tax extends MainModel
{
    use HasFactory,HasTranslations;

    protected array $translatable=['name'];
    protected $table='taxes';
    protected $guard_name = 'web';

    public function taxComponents(){
        return $this->hasMany(TaxComponent::class,'tax_id');
    }
    public function cateogry(){
        return $this->hasMany(Category::class,'category_id');
    }
    public function product(){
        return $this->hasMany(Product::class,'tax_id');
    }
    public function tax(){
        return $this->hasMany(Tax::class,'tax_id');
    }
    public function brand(){
        return $this->hasMany(Brand::class,'brand_id');
    }

    public function getComplexPrice( $price,array $allTaxComponents = [],$allTaxes = []) :float{

        $allTaxComponents = count($allTaxComponents) != 0 ? $allTaxComponents : TaxComponent::all()->toArray();
        $allTaxes = count($allTaxes) != 0 ? $allTaxes : Tax::all()->toArray();
        $allTaxes = collect($allTaxes);
        $neededTaxComponents = collect($allTaxComponents)->where('tax_id',$this->id)->toArray();

        $resultantTaxRate = 0.0;
        $totalTax = 0.0;
        if($this->complex_behavior == 'combine'){
            foreach($neededTaxComponents as $neededTaxComponent){
                $tax = $allTaxes->where('id',$neededTaxComponent['id'])->first();
                $totalTax += $tax['percentage'];
            }
            $resultantTaxRate = $totalTax * $price / 100;
        }else{
            foreach($neededTaxComponents as $neededTaxComponent){
                $tax = $allTaxes->where('id',$neededTaxComponent['id'])->first();
                $tempTax = ($tax['percentage'] * $price) / 100;
                $price += $tempTax;
                $totalTax = $tempTax;
            }
            $resultantTaxRate = $totalTax;
        }

        return $resultantTaxRate;
    }
}
