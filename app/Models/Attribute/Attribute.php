<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attribute\AttributeValue;
use App\Models\Product\Product;

class Attribute extends Model
{
    use HasFactory;
    protected $table='attributes';
    protected $guard_name = 'sanctum';

    public function attributeValue(){
        return $this->hasMany(AttributeValue::class,'attribute_value_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');

    }


}
