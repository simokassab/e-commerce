<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attribute;
use App\Models\Product;

class AttributeValue extends Model
{
    use HasFactory;
    protected $table='attributes_values';

    public function attribute(){
        return $this->belongsTo(Attribute::class,'attribute_id');
        }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');

    }
}
