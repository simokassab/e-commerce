<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRelated extends Model
{
    use HasFactory;
    protected $table = 'products_related';

    protected $guarded = [];
}
