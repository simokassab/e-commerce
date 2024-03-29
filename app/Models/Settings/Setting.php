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

    public static array $titles = [
        'products_required_fields',
        'products_quantity_greater_than_or_equal',
        'allow_negative_quantity',
        'products_prices_greater_than_or_equal',
        'products_discounted_price_greater_than_or_equal',
        'is_discount_on_shipping',
        'default_pricing_class'
    ];

    public static string $cacheKey = 'settings';

    public static array $types = ['multi-select', 'select', 'number', 'text', 'checkbox', 'model_select'];

    public static function getTitleOptions()
    {
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
                    'name' => 'brand'
                ],
                [
                    'id' => 'tax_id',
                    'name' => 'tax'
                ],

            ],
            'default_pricing_class' => Price::whereIs_virtual(0)->select('id', 'name')->get()->toArray() ,
        ];

        return $titlesOptions;
    }


    public static function validateOptionsByTitle($keyTitle, $givenOptions)
    {
        $returnBool = true; //1. if the title is not in the array of titlesOptions, return true
        // dd(Setting::getTitleOptions()[$keyTitle][0]['name']);
        $optionsArray=Setting::getTitleOptions()[$keyTitle];
        foreach ($optionsArray as $key => $dbOption) {
            $optionsArray[$key]=$dbOption['id'];
        }
        foreach ($givenOptions as $key => $option)
            $returnBool &= in_array($option, $optionsArray);

        return $returnBool;
    }
}
