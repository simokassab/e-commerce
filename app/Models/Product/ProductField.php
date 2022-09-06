<?php

namespace App\Models\Product;

use App\Models\MainModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
class ProductField extends MainModel
{

    use HasFactory, HasTranslations;
    protected $table = 'products_fields';
    protected $translatable = [];
    protected $fillable=[
        'product_id',
        'field_id',
        'field_value_id',
        'value',
    ];
}
