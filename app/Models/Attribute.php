<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttributeValue;
use App\Models\Product;

class Attribute extends Model
{
    use HasFactory;
    protected $table='attributes';

    public function attributeValue(){
        return $this->hasMany(AttributeValue::class,'attribute_value_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');

    }


}
