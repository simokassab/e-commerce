<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;

class Setting extends MainModel
{
<<<<<<< HEAD
    use HasFactory;

    protected $translatable=[];
    protected $fillable = ['title','value','type','is_developer'];

    public static $titlesOptions = [
        'products_required_fields' =>  ['sku','summary','specification','barcode','length','height','width','weight','brand_id','tax_id'],
        'products_quantity_greater_than_or_equal'=>[],
        'products_minimum_and_reserved_quantity_greater_than_or_equal'=>[],
        'products_prices_greater_than_or_equal'=>[],
        'products_discounted_price_greater_than_or_equal'=>[],
    ];

    public static function validateOptionsByTitle($keyTitle,$givenOptions){
        $returnBool = true; //1. if the title is not in the array of titlesOptions, return true
        foreach ($givenOptions as $option)
            $returnBool &= in_array($option, Setting::$titlesOptions[$keyTitle]);

        return $returnBool;
    }


=======
    use HasFactory, HasTranslations;

    protected $translatable=[];
    protected $fillable = ['title','value','is_developer'];
    protected $guard_name = 'web';
>>>>>>> product-v2

}
