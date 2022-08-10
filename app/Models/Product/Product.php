<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category\Category;
use App\Models\Unit\Unit;
use App\Models\Tax\Tax;
use App\Models\Brand\Brand;
use App\Models\Price\Price;
use App\Models\Tag\Tag;
use App\Models\Product\ProductImage;
use App\Models\Label\Label;
use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeValue;
use App\Models\Field\Field;
use App\Models\Field\FieldValue;
use App\Models\MainModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Translatable\HasTranslations;

class Product extends MainModel
{
    use HasFactory,HasTranslations;
    protected $translatable=['name','summary','specification','description','meta_title','meta_description','meta_keywords'];
    protected $table='products';
    protected $guard_name = 'web';
    protected $guarded=[];


    public function parent(){
        return $this->belongsTo(Product::class,'parent_product_id','id');
    }

    public function children(){
        return $this->hasMany(Product::class,'parent_product_id');
    }

    public function category(){
        return $this->belongsToMany(Category::class,'products_categories','product_id','category_id');
    }
    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id');
    }
    public function tax(){
        return $this->belongsTo(Tax::class,'tax_id','id');
    }
    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function price(){
        return $this->belongsTo(Price::class,'price_id');
    }

    public function pricesList(){
        return $this->hasMany(ProductPrice::class, 'product_id','id');
    }

    public function productRelatedParent(){
        return $this->belongsTo(Product::class,'parent_product_id');

    }
    public function productRelatedChildren(){
        return $this->hasMany(Product::class,'child_product_id');

    }
    public function productImages(){
        return $this->hasMany(ProductImage::class,'product_id');
    }

    public function defaultCategory(){
        return $this->belongsTo(Category::class,'category_id');

    }

    public function tags(){
        return $this->belongsToMany(Tag::class,'products_tags','product_id','tag_id');


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

    public function getVirtualPricing(Price | int $pricingClass){
        $pricingClass  = is_int($pricingClass)  ?  Price::findOrFail($pricingClass) : $pricingClass ;
        $originalPricingClass = $pricingClass->originalPrice;
        if(!$originalPricingClass){
            return 0;
        }
        if(!$pricingClass->is_virtual){
            return 0;
        }
        $originalPricingClassId = $originalPricingClass->id;
        $productPricing = ProductPrice::where('product_id' ,$this->id )->where('price_id', $originalPricingClassId)->first();
        if($productPricing == null){
            return 0;
        }
        return ($productPricing->price * $pricingClass->percentage)/100.0;


    }

    public function getPrice(int $pricingClassId){
        $pricingClass  = Price::findOrFail($pricingClassId);
        if($pricingClass->is_virtual){
            return $this->getVirtualPricing($pricingClassId);
        }
        $productPricing = ProductPrice::where('product_id' ,$this->id )->where('price_id', $pricingClassId)->first();
        return is_null($productPricing) ? 0 : $productPricing->price;

    }

}



