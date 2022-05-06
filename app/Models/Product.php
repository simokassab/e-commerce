<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\Brand;
use App\Models\Price;
use App\Models\Tag;
use PDO;
use App\Models\ProductImage;
use App\Models\Label;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Field;
use App\Models\FieldValue;

class Product extends Model
{
    use HasFactory;
    protected $table='products';

    public function parent(){
        return $this->belongsTo(Product::class,'parent_product_id');
    }

    public function children(){
        return $this->hasMany(Product::class,'parent_product_id');
    }

    public function cateogry(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id');
    }
    public function tax(){
        return $this->belongsTo(Tax::class,'tax_id');
    }
    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function price(){
        return $this->belongsTo(Price::class,'price_id');
    }

    public function productRelatedParent(){
        return $this->belongsTo(Product::class,'parent_product_id');

    }
    public function productRelatedChilds(){
        return $this->hasMany(Product::class,'child_product_id');

    }
    public function productImages(){
        return $this->hasMany(ProductImage::class,'product_id');
    }

    public function productCategory(){
        return $this->hasMany(Category::class,'category_id');

    }

    public function tags(){
        return $this->hasMany(Tag::class,'tag_id');

    }
    public function labels(){
        return $this->hasMany(Label::class,'label_id');

    }
    public function attribute(){
        return $this->hasMany(Attribute::class,'attribute_id');

    }
    public function attributeValue(){
        return $this->hasMany(AttributeValue::class,'attribute_value_id');

    }

    public function field(){
        return $this->hasMany(Field::class,'field_id');

    }
    public function fieldValue(){
        return $this->hasMany(FieldValue::class,'field_value_id');

    }
}
