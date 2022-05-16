<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category\Category;
use App\Models\Unit\Unit;
use App\Models\Tax\Tax;
use App\Models\Brand\Brand;
use App\Models\Price\Price;
use App\Models\Tag\Tag;
use PDO;
use App\Models\Product\ProductImage;
use App\Models\Label\Label;
use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeValue;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use App\Models\MainModel;

class Product extends MainModel
{
    use HasFactory;
    protected $table='products';
    protected $guard_name = 'sanctum';

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
