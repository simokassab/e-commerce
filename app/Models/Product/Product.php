<?php

namespace App\Models\Product;

use App\Models\Settings\Setting;
use Exception;
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
use phpDocumentor\Reflection\Types\False_;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\DB;

class Product extends MainModel
{
    use HasFactory,HasTranslations;
    protected $translatable=['name','summary','specification','description','meta_title','meta_description','meta_keyword'];
    protected $table='products';
    protected $guard_name = 'web';

    public $fillable = [
        'name',
        'slug',
        'code',
        'sku',
        'type',
        'quantity',
        'reserved_quantity',
        'summary',
        'specification',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'description',
        'status',
        'barcode',
        'height',
        'width',
        'length',
        'weight',
        'is_default_child',
        'parent_product_id',
        'category_id',
        'unit_id',
        'brand_id',
        'tax_id',
        'products_statuses_id',
        'is_show_related_product',
    ];

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

    public function priceClass(){
        return $this->belongsToMany(Price::class,'products_prices','product_id','price_id');
    }

    public function pricesList(){
        return $this->hasMany(ProductPrice::class, 'product_id','id');
    }

    public function price(){
        return $this->hasMany(ProductPrice::class,'product_id','id');
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
        return $this->belongsToMany(Label::class,'products_labels','product_id','label_id');

    }
    public function attribute(){
        return $this->hasMany(Attribute::class,'attribute_id');

    }
    public function attributeValue(){
        return $this->hasMany(AttributeValue::class,'attribute_value_id');

    }

    public function field(){
        return $this->belongsToMany(Field::class,'products_fields','product_id','field_id');

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

    public function getPriceRelation(){
        return $this->price();
    }

    /**
     * @throws Exception
     */
    public function updateProductQuantity(int $quantity, string $method){

            if($method != 'add' && $method != 'sub'){
                throw new \Exception('Bad method type '.$method);
            }
            if($this->type == 'service' || $this->type == 'variable'){
                return $this;
            }
            if($this->type == 'normal' || $this->type == 'variable_child'){
                if($method == 'add'){
                    return $this->addQuantityForNormalAndVariableChild($quantity);
                }else{
                    return $this->subQuantityForNormalAndVariableChild($quantity);
                }
            }if($this->type == 'bundle'){
                if($method == 'add'){
                    return $this->addQuantityForBundle($quantity);
                }else{
                    return $this->subQuantityForBundle($quantity);
                }
            }

            throw new Exception('The type of product is invalid '.$this->type);


    }

    protected function addQuantityForNormalAndVariableChild(int $quantity){
        $this->quantity += $quantity;
        if($this->save())
            return $this;

        throw new \Exception('An error occurred please try again !');
    }

    /**
     * @throws Exception
     */
    protected function subQuantityForNormalAndVariableChild(int $quantity){
        //TODO: change the settings instead of sending a query get them from the cache
        $isAllowNegativeQuantity = Setting::where('title','allow_negative_quantity')->first();
        if($isAllowNegativeQuantity){
            $this->quantity -= $quantity;
            if($this->save())
                return $this;
            throw new \Exception('An error occurred please try again !');
        }
        if($this->pre_order){
            $this->quantity -= $quantity;
            if($this->save())
                return $this;
            throw new \Exception('An error occurred please try again !');
        }

        if($this->quantity < $quantity){
            throw new Exception('You have less quantity than '. $quantity .' in stock');
        }

        $this->quantity -= $quantity;
        if($this->save())
            return $this;
        throw new \Exception('An error occurred please try again !');


    }

    /**
     * @throws Exception
     */
    private function addQuantityForBundle(int $quantity, array $allProducts = [], array $allRelatedProducts = [])
    {
        $allProducts = count($allProducts) > 0 ? $allProducts : self::all();
        $allRelatedProducts = count($allRelatedProducts) > 0 ? $allRelatedProducts : ProductRelated::all();
        $relatedProducts = collect($allRelatedProducts)->where('parent_product_id',$this->id);
        $relatedProductsIds = $relatedProducts->pluck('child_product_id');
        $products = $allProducts->whereIn('id',$relatedProductsIds);

        //TODO: change the settings instead of sending a query get them from the cache
        $isAllowNegativeQuantity = (bool)Setting::where('title','allow_negative_quantity')->first()->value;
        if($isAllowNegativeQuantity){
            foreach ($products as $product) {
                $productModel = self::find($product->id);
                $childRelatedProduct = $relatedProducts
                    ->where('child_product_id',$product->id)
                    ->where('parent_product_id',$this->id)
                    ->first();

                $productModel->bundle_reserved_quantity += $quantity * $childRelatedProduct->child_quantity;
                if(!$productModel->save()){
                    throw new Exception('One of the related products for bundle was not saved correctly! try again later ');
                }

            }

            $this->reserved_quantity += $quantity;
            $this->quantity = 0;
            if(!$this->save()){
                throw new \Exception('An error occurred please try again !');
            }
            return $this;

        }

        if($this->pre_order){
            foreach ($products as $product) {
                $productModel = self::find($product->id);
                $childRelatedProduct = $relatedProducts
                    ->where('child_product_id',$product->id)
                    ->where('parent_product_id',$this->id)
                    ->first();

                $productModel->bundle_reserved_quantity += $quantity * $childRelatedProduct->child_quantity;
                if(!$productModel->save()){
                    throw new Exception('One of the related products for bundle was not saved correctly! try again later ');
                }

            }

            $this->reserved_quantity += $quantity;
            $this->quantity = 0;
            if(!$this->save()){
                throw new \Exception('An error occurred please try again !');
            }
            return $this;
        }
        if(!$this->hasEnoughRelatedProductsQuantity($quantity,$allProducts->toArray(),$allRelatedProducts->toArray())){
            throw new Exception('Please try again later');
        }

            $relatedProducts = collect($allRelatedProducts)->where('parent_product_id',$this->id);
            $relatedProductsIds = $relatedProducts->pluck('child_product_id');
            $products = $allProducts->whereIn('id',$relatedProductsIds);

            foreach ($products as $product) {
                $productModel = self::find($product->id);
                $childRelatedProduct = $relatedProducts
                    ->where('child_product_id',$product->id)
                    ->where('parent_product_id',$this->id)
                    ->first();
                $productModel->bundle_reserved_quantity += $quantity * $childRelatedProduct->child_quantity;
                if(!$productModel->save()){
                throw new Exception('An error occurred please try again later');
                }
            }

            $this->reserved_quantity += $quantity;
            $this->quantity = 0;
            if($this->save())
                return $this;
            throw new \Exception('An error occurred please try again !');



    }

    /**
     * @throws Exception
     */
    private function subQuantityForBundle(int $quantity, array $allProducts = [], array $allRelatedProducts = [])
    {
        $allProducts = count($allProducts) > 0 ? $allProducts : self::all();
        $allRelatedProducts = count($allRelatedProducts) > 0 ? $allRelatedProducts : ProductRelated::all();
            //TODO: take settings from cache
            $isAllowNegativeQuantity = Setting::where('title','allow_negative_quantity')->first()->value;

            $relatedProducts = collect($allRelatedProducts)->where('parent_product_id',$this->id);
            $relatedProductsIds = $relatedProducts->pluck('child_product_id');
            $products = $allProducts->whereIn('id',$relatedProductsIds);

            if($isAllowNegativeQuantity){

                foreach ($products as $product) {
                    $productModel = self::find($product->id);
                    $childRelatedProduct = $relatedProducts
                        ->where('child_product_id',$product->id)
                        ->where('parent_product_id',$this->id)
                        ->first();
                    $productModel->bundle_reserved_quantity -= $quantity * $childRelatedProduct->child_quantity;
                    if(!$productModel->save()){
                        throw new Exception('One of the related products for bundle was not saved correctly! try again later ');
                    }

                }

                $this->reserved_quantity -= $quantity;
                $this->quantity = 0;
                if(!$this->save())
                    throw new \Exception('An error occurred please try again !');
                return $this;
            }

        if($this->pre_order){
            foreach ($products as $product) {
                $productModel = self::find($product->id);
                $childRelatedProduct = $relatedProducts
                    ->where('child_product_id',$product->id)
                    ->where('parent_product_id',$this->id)
                    ->first();
                $productModel->quantity -= $quantity * $childRelatedProduct->child_quantity;
                $productModel->bundle_reserved_quantity -= $quantity * $childRelatedProduct->child_quantity;
                if(!$productModel->save()){
                    throw new Exception('One of the related products for bundle was not saved correctly! try again later ');
                }

            }

            $this->reserved_quantity -= $quantity;
            $this->quantity = 0;
            if(!$this->save())
                throw new \Exception('An error occurred please try again !');
            return $this;
        }

            if( !$this->hasEnoughRelatedProductsQuantity($quantity,$allProducts->toArray(),$allRelatedProducts->toArray()) ){
                throw new Exception('Not enough quantity for children products !');
            }

            if($this->reserved_quantity < $quantity){
                throw new Exception('Not enough quantity for product !');
            }

            foreach ($products as $product) {
                $productModel = self::find($product['id']);
                $childRelatedProduct = $relatedProducts
                    ->where('child_product_id',$product['id'])
                    ->where('parent_product_id',$this->id)
                    ->first();
                $productModel->quantity -= $quantity * $childRelatedProduct->child_quantity;
                $productModel->bundle_reserved_quantity -= $quantity * $childRelatedProduct->child_quantity;
                if(!$productModel->save()){
                    throw new Exception('One of the related products for bundle was not saved correctly! try again later ');
                }

            }

            $this->reserved_quantity -= $quantity;
            $this->quantity = 0;
            if(!$this->save())
                throw new \Exception('An error occurred please try again !');

            return $this;


    }

    /**
     * @throws Exception
     *
     */
    public function hasEnoughRelatedProductsQuantity(int $quantity, array $allProducts = [], array $allRelatedProducts = []):bool{
        if($this->type != 'bundle'){
            throw new Exception('Call hasEnoughRelatedProductsQuantity on wrong product type not bundle');
        }

        $allProducts = count($allProducts) > 0 ? $allProducts : self::all();
        $allRelatedProducts = count($allRelatedProducts) > 0 ? $allRelatedProducts : ProductRelated::all();
        //TODO: to make the loop faster convert the huge array into chunks and loop over the chunks
        $relatedProducts = collect($allRelatedProducts)->where('parent_product_id',$this->id);
        $relatedProductsIds = $relatedProducts->pluck('child_product_id');
        $childrenProducts = collect($allProducts)->whereIn('id',$relatedProductsIds);
        $i = 0;
        foreach ($childrenProducts as $childProduct){
            $i ++ ;
            $childRelatedProduct = $relatedProducts
                ->where('child_product_id',$childProduct['id'])
                ->where('parent_product_id',$this->id)
                ->first();

            if($childProduct['bundle_reserved_quantity'] < 0 ){
                $childProduct['bundle_reserved_quantity'] *= -1;
            }

            $childProduct['quantity'] -= $childProduct['bundle_reserved_quantity'];

            if($childRelatedProduct['child_quantity'] < 0 ){
                $childRelatedProduct['child_quantity'] *= -1;
            }

            $quantityToBeReserved = $childRelatedProduct['child_quantity'] * $quantity;
            if($quantityToBeReserved > $childProduct['quantity']){
                return false;
            }
        }
        return true;

    }

//    public function recalculateBundleReservedQuantity( array $allProducts = [], array $allRelatedProducts = []){
//        $allProducts = count($allProducts) > 0 ? $allProducts : self::all();
//        $allRelatedProducts = count($allRelatedProducts) > 0 ? $allRelatedProducts : ProductRelated::all();
//
//
//        $relatedProducts = collect($allRelatedProducts)->where('parent_product_id' ,$this->id)->get();
//        $relatedProductsIds = $relatedProducts->pluck('id');
//        $products = $allProducts->whereIn('id',$relatedProductsIds)->get();
//
//        foreach ($products as $product){
//
//        }
//    }


}



