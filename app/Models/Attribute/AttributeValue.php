<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MainModel;
use App\Models\Attribute\Attribute;
use App\Models\Product\Product;

class AttributeValue extends MainModel
{
    use HasFactory;
    protected $table='attributes_values';
    protected $guard_name = 'sanctum';

    public function attribute(){
        return $this->belongsTo(Attribute::class,'attribute_id');
        }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');

    }
}
