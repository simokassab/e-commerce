<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Price\Price;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;

class Setting extends MainModel
{
    use HasFactory;

    protected $translatable = [];
    protected $fillable = ['title', 'value', 'type', 'is_developer'];
    public $fields = [
        'products_required_fields',
        'products_quantity_greater_than_or_equal' ,
        'allow_negative_quantity',
        'products_prices_greater_than_or_equal',
        'products_discounted_price_greater_than_or_equal',
        'is_discount_on_shipping'
    ];
    // public function getPriceOptions(){
    //     dd($prices);
    // }
public static function getTitleOptions(){
    $titlesOptions = [
        'products_required_fields' =>  [
            [
                'id' => 'sku',
                'name' => 'sku'
            ],
            [
                'id' => 'summary',
                'name' => 'summary'
            ],
            [
                'id' => 'specification',
                'name' => 'specification'
            ],
            [
                'id' => 'barcode',
                'name' => 'barcode'
            ],
            [
                'id' => 'length',
                'name' => 'length'
            ],
            [
                'id' => 'height',
                'name' => 'height'
            ],
            [
                'id' => 'width',
                'name' => 'width'
            ],
            [
                'id' => 'weight',
                'name' => 'weight'
            ],
            [
                'id' => 'brand_id',
                'name' => 'brand_id'
            ],
            [
                'id' => 'tax_id',
                'name' => 'tax_id'
            ],

        ],
        'products_quantity_greater_than_or_equal' => [],
        'allow_negative_quantity' => [],
        'products_prices_greater_than_or_equal' => [],
        'products_discounted_price_greater_than_or_equal' => [],
        'is_discount_on_shipping' => []
    ];
    $titlesOptions['website_pricing']= Price::all('id','name')->toArray();
    foreach ($titlesOptions['website_pricing'] as $key => $option)
        $titlesOptions[$key]['name'] = $option['name']['en'];

    return $titlesOptions;
}


    public static function validateOptionsByTitle($keyTitle, $givenOptions)
    {


        $returnBool = true; //1. if the title is not in the array of titlesOptions, return true
        foreach ($givenOptions as $option)
            $returnBool &= in_array($option, Setting::$titlesOptions[$keyTitle]);

        return $returnBool;
    }
}
