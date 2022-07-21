<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;

class Setting extends MainModel
{
    use HasFactory;

    protected $translatable=[];
    protected $fillable = ['title','value','is_developer'];

    public static $titlesArray=[
        'products_required_fields',
        'products_quantity_greater_than_or_equal',
        'products_minimum_and_reserved_quantity_greater_than_or_equal',
        'products_prices_greater_than_or_equal',
        'products_discounted_price_greater_than_or_equal',
    ];
    public static $titlesTypes = [
        'multi-select',
        'number',
        'number',
        'number',
        'number',
    ];
    public static $titlesOptions = [
        ['sku','summary','specification','barcode','length','height','width','weight','brand_id','tax_id'],
        [],
        [],
        [],
        [],
    ];

}
