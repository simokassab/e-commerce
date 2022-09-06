<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductField extends Model
{
    use HasFactory;
    protected $table = 'products_fields';
    protected $translatable = [];
    protected $fillable=[
        'product_id',
        'field_id',
        'field_value_id',
        'value'
    ];
}
