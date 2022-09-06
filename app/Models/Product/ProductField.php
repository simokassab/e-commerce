<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class ProductField extends Model
{

    public function __construct(){}

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
